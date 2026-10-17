<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Tests\integration\notifications;

use Carbon\Carbon;
use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Notifying the people who follow a discussion's tags looks their
 * subscriptions up together, not one person and tag at a time.
 *
 * Two people lurk in General and eight in Testing: the same request is sent
 * for each tag, and must take as many subscription queries for eight
 * recipients as for two.
 */
class NotificationQueriesTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use ExtensionDepsTrait;
    use TagsDefinitionTrait;

    /**
     * Each recipient is still checked on their own: whether they can see the
     * discussion and its first post depends on their permissions (discussions,
     * posts, groups, and the tag counts flarum/tags scopes visibility by), and
     * core fetches each recipient again to send them their notification
     * (users), making an unsubscribe link for each email (unsubscribe_tokens).
     * The subscription lookups these tests are about are not here.
     */
    protected function allowedRepeatedQueries(): array
    {
        return [
            'from `discussions`', 'from `posts`', 'from `groups`', 'as primary_count',
            'from `users` where `users`.`id` = ?', 'into `unsubscribe_tokens`',
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionDeps();

        $lurkers = ['General' => range(10, 11), 'Testing' => range(20, 27)];

        $this->prepareDatabase([
            User::class => array_map(fn (int $id) => $this->user($id), array_merge(...array_values($lurkers))),
            Tag::class  => $this->tags(),
            'tag_user'  => array_merge(
                array_map(fn (int $id) => $this->lurking($id, 1), $lurkers['General']),
                array_map(fn (int $id) => $this->lurking($id, 2), $lurkers['Testing']),
            ),
            Discussion::class => [
                $this->discussion(1, 'In General'),
                $this->discussion(2, 'In Testing'),
                $this->discussion(3, 'In the Playground'),
            ],
            Post::class => [
                $this->post(1, 1),
                $this->post(2, 2),
                $this->post(3, 3),
            ],
            'discussion_tag' => [
                ['discussion_id' => 1, 'tag_id' => 1],
                ['discussion_id' => 2, 'tag_id' => 2],
                ['discussion_id' => 3, 'tag_id' => 3],
            ],
            'discussion_user' => array_merge(
                array_map(fn (int $id) => ['user_id' => $id, 'discussion_id' => 1, 'last_read_post_number' => 1], $lurkers['General']),
                array_map(fn (int $id) => ['user_id' => $id, 'discussion_id' => 2, 'last_read_post_number' => 1], $lurkers['Testing']),
            ),
        ]);
    }

    #[Test]
    public function a_new_discussion_looks_up_its_followers_subscriptions_together()
    {
        $this->assertSubscriptionQueriesDoNotGrow(
            $this->startDiscussion(1),
            $this->startDiscussion(2),
            'newDiscussionInTag'
        );
    }

    #[Test]
    public function a_reply_looks_up_its_lurkers_subscriptions_together()
    {
        $this->assertSubscriptionQueriesDoNotGrow(
            $this->reply(1),
            $this->reply(2),
            'newPostInTag'
        );
    }

    #[Test]
    public function a_retagged_discussion_looks_up_its_followers_subscriptions_together()
    {
        $this->assertSubscriptionQueriesDoNotGrow(
            $this->retag(3, 1),
            $this->retag(3, 2),
            'newDiscussionTag'
        );
    }

    protected function assertSubscriptionQueriesDoNotGrow(ServerRequestInterface $forTwo, ServerRequestInterface $forEight, string $type): void
    {
        $two = $this->subscriptionQueries($forTwo);
        $notifiedOfFirst = $this->database()->table('notifications')->where('type', $type)->count();
        $eight = $this->subscriptionQueries($forEight);
        $notifiedOfSecond = $this->database()->table('notifications')->where('type', $type)->count() - $notifiedOfFirst;

        $this->assertEquals([2, 8], [$notifiedOfFirst, $notifiedOfSecond], 'The wrong number of people were notified.');
        $this->assertSame($two, $eight, "Looking up subscriptions took $two queries for two recipients and $eight for eight.");
    }

    /**
     * How many queries a request ran against tag subscriptions.
     */
    protected function subscriptionQueries(ServerRequestInterface $request): int
    {
        $database = $this->database();
        $database->flushQueryLog();
        $database->enableQueryLog();

        try {
            $response = $this->send($request);
            $queries = array_column($database->getQueryLog(), 'query');
        } finally {
            $database->disableQueryLog();
            $database->flushQueryLog();
        }

        $this->assertContains($response->getStatusCode(), [200, 201], (string) $response->getBody());

        return count(array_filter($queries, fn (string $sql) => str_contains($sql, 'tag_user')));
    }

    protected function startDiscussion(int $tagId): ServerRequestInterface
    {
        return $this->request('POST', '/api/discussions', [
            'authenticatedAs' => 1,
            'json'            => ['data' => [
                'attributes'    => ['title' => 'A new discussion', 'content' => 'Its first post'],
                'relationships' => ['tags' => ['data' => [['type' => 'tags', 'id' => $tagId]]]],
            ]],
        ]);
    }

    protected function reply(int $discussionId): ServerRequestInterface
    {
        return $this->request('POST', '/api/posts', [
            'authenticatedAs' => 1,
            'json'            => ['data' => [
                'attributes'    => ['content' => 'A reply'],
                'relationships' => ['discussion' => ['data' => ['type' => 'discussions', 'id' => $discussionId]]],
            ]],
        ]);
    }

    protected function retag(int $discussionId, int $tagId): ServerRequestInterface
    {
        return $this->request('PATCH', "/api/discussions/$discussionId", [
            'authenticatedAs' => 1,
            'json'            => ['data' => [
                'type'          => 'discussions',
                'id'            => (string) $discussionId,
                'relationships' => ['tags' => ['data' => [['type' => 'tags', 'id' => $tagId]]]],
            ]],
        ]);
    }

    protected function lurking(int $userId, int $tagId): array
    {
        return ['user_id' => $userId, 'tag_id' => $tagId, 'subscription' => 'lurk', 'is_hidden' => 0, 'created_at' => Carbon::now()->toDateTimeString()];
    }

    protected function user(int $id): array
    {
        return [
            'id'                 => $id,
            'username'           => "user$id",
            'email'              => "user$id@machine.local",
            'is_email_confirmed' => 1,
            'preferences'        => json_encode([
                'notify_newDiscussionInTag_alert' => true,
                'notify_newPostInTag_alert'       => true,
                'notify_newDiscussionTag_alert'   => true,
            ]),
        ];
    }

    protected function discussion(int $id, string $title): array
    {
        return [
            'id'               => $id, 'title' => $title, 'user_id' => 1,
            'created_at'       => Carbon::now()->subDay()->toDateTimeString(),
            'first_post_id'    => $id, 'comment_count' => 1, 'last_post_number' => 1,
        ];
    }

    protected function post(int $id, int $discussionId): array
    {
        return [
            'id'         => $id, 'discussion_id' => $discussionId, 'user_id' => 1, 'type' => 'comment',
            'content'    => '<t><p>The first post</p></t>', 'number' => 1,
            'created_at' => Carbon::now()->subDay()->toDateTimeString(),
        ];
    }
}

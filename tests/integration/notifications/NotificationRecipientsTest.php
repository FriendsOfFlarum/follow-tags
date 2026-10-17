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
use Flarum\Group\Group;
use Flarum\Notification\Notification;
use Flarum\Post\Post;
use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * Who is notified of new discussions, replies and retagged discussions: those
 * following (or, for replies, lurking in) one of its tags, who can see it and
 * ignore none of its tags.
 */
class NotificationRecipientsTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use ExtensionDepsTrait;
    use TagsDefinitionTrait;

    /**
     * Core queues an email to each recipient, and each email job fetches its
     * recipient again.
     */
    protected function allowedRepeatedQueries(): array
    {
        $grammar = $this->database()->getQueryGrammar();

        return ['from '.$grammar->wrapTable('users').' where '.$grammar->wrap('users.id').' = ?'];
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionDeps();

        $this->prepareDatabase([
            User::class => [
                $this->user(2), // follows General
                $this->user(3), // lurks in Testing
                $this->user(4), // follows General, ignores Testing
                $this->user(6), // no subscriptions
                $this->user(7), // lurks in Archive, which they can't see
                $this->user(8), // lurks in Archive, which their group can see
                $this->user(9), // lurks in Testing, ignores General
            ],
            Group::class => [
                ['id' => 5, 'name_singular' => 'Archivist', 'name_plural' => 'Archivists'],
            ],
            'group_user' => [
                ['user_id' => 8, 'group_id' => 5],
            ],
            'group_permission' => [
                ['group_id' => 5, 'permission' => 'tag4.viewForum'],
            ],
            Tag::class => $this->tags(),
            'tag_user' => array_map(fn (array $state) => $state + ['is_hidden' => 0, 'created_at' => Carbon::now()->toDateTimeString()], [
                ['user_id' => 1, 'tag_id' => 1, 'subscription' => 'follow'],
                ['user_id' => 2, 'tag_id' => 1, 'subscription' => 'follow'],
                ['user_id' => 3, 'tag_id' => 2, 'subscription' => 'lurk'],
                ['user_id' => 4, 'tag_id' => 1, 'subscription' => 'follow'],
                ['user_id' => 4, 'tag_id' => 2, 'subscription' => 'ignore'],
                ['user_id' => 7, 'tag_id' => 4, 'subscription' => 'lurk'],
                ['user_id' => 8, 'tag_id' => 4, 'subscription' => 'lurk'],
                ['user_id' => 9, 'tag_id' => 2, 'subscription' => 'lurk'],
                ['user_id' => 9, 'tag_id' => 1, 'subscription' => 'ignore'],
            ]),
            Discussion::class => [
                $this->discussion(1, 'In General and Testing'),
                $this->discussion(2, 'In the Archive'),
                $this->discussion(3, 'In the Playground'),
            ],
            Post::class => [
                $this->post(1, 1),
                $this->post(2, 2),
                $this->post(3, 3),
            ],
            'discussion_tag' => [
                ['discussion_id' => 1, 'tag_id' => 1],
                ['discussion_id' => 1, 'tag_id' => 2],
                ['discussion_id' => 2, 'tag_id' => 4],
                ['discussion_id' => 3, 'tag_id' => 3],
            ],
            // Everyone has read every discussion so far.
            'discussion_user' => array_merge(...array_map(fn (int $userId) => [
                ['user_id' => $userId, 'discussion_id' => 1, 'last_read_post_number' => 1],
                ['user_id' => $userId, 'discussion_id' => 2, 'last_read_post_number' => 1],
                ['user_id' => $userId, 'discussion_id' => 3, 'last_read_post_number' => 1],
            ], range(2, 9))),
        ]);
    }

    #[Test]
    public function a_new_discussion_notifies_those_following_its_tags_who_ignore_none_of_them()
    {
        $this->assertEquals(201, $this->startDiscussion([1, 2]));

        // 4 and 9 each ignore one of its tags.
        $this->assertEquals([2, 3], $this->recipients('newDiscussionInTag'));
    }

    #[Test]
    public function a_new_discussion_in_a_restricted_tag_notifies_only_those_who_can_see_it()
    {
        $this->assertEquals(201, $this->startDiscussion([4]));

        $this->assertEquals([8], $this->recipients('newDiscussionInTag'));
    }

    #[Test]
    public function a_reply_notifies_those_lurking_in_its_tags_who_ignore_none_of_them()
    {
        $this->assertEquals(201, $this->reply(1));

        // Following a tag (2, 4) is for new discussions, not replies.
        $this->assertEquals([3], $this->recipients('newPostInTag'));
    }

    #[Test]
    public function a_reply_in_a_restricted_tag_notifies_only_those_who_can_see_it()
    {
        $this->assertEquals(201, $this->reply(2));

        $this->assertEquals([8], $this->recipients('newPostInTag'));
    }

    #[Test]
    public function a_retagged_discussion_notifies_those_following_its_new_tags_who_ignore_none_of_them()
    {
        $this->assertEquals(200, $this->retag(3, [1, 2]));

        $this->assertEquals([2, 3], $this->recipients('newDiscussionTag'));
    }

    #[Test]
    public function a_discussion_retagged_into_a_restricted_tag_notifies_only_those_who_can_see_it()
    {
        $this->assertEquals(200, $this->retag(3, [4]));

        $this->assertEquals([8], $this->recipients('newDiscussionTag'));
    }

    protected function startDiscussion(array $tagIds): int
    {
        return $this->send(
            $this->request('POST', '/api/discussions', [
                'authenticatedAs' => 1,
                'json'            => ['data' => [
                    'attributes'    => ['title' => 'A new discussion', 'content' => 'Its first post'],
                    'relationships' => ['tags' => ['data' => array_map(fn (int $id) => ['type' => 'tags', 'id' => $id], $tagIds)]],
                ]],
            ])
        )->getStatusCode();
    }

    protected function reply(int $discussionId): int
    {
        return $this->send(
            $this->request('POST', '/api/posts', [
                'authenticatedAs' => 1,
                'json'            => ['data' => [
                    'attributes'    => ['content' => 'A reply'],
                    'relationships' => ['discussion' => ['data' => ['type' => 'discussions', 'id' => $discussionId]]],
                ]],
            ])
        )->getStatusCode();
    }

    protected function retag(int $discussionId, array $tagIds): int
    {
        return $this->send(
            $this->request('PATCH', "/api/discussions/$discussionId", [
                'authenticatedAs' => 1,
                'json'            => ['data' => [
                    'type'          => 'discussions',
                    'id'            => (string) $discussionId,
                    'relationships' => ['tags' => ['data' => array_map(fn (int $id) => ['type' => 'tags', 'id' => $id], $tagIds)]],
                ]],
            ])
        )->getStatusCode();
    }

    /**
     * @return int[]
     */
    protected function recipients(string $type): array
    {
        return Notification::query()->where('type', $type)->orderBy('user_id')->pluck('user_id')->all();
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

<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Tests\integration\api;

use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * Serializing many tags takes the same queries as serializing a few: the
 * test case fails any request that runs one query per tag.
 *
 * Eight primary tags each have one child, and each discussion is tagged with
 * its child only, so the parents reach the response through an include. The
 * member follows some parents and children; another member's subscriptions
 * must not show through.
 */
class SubscriptionQueriesTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use ExtensionDepsTrait;

    protected const PARENTS = 8;

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionDeps();

        $tags = $discussions = $posts = $discussionTags = [];

        for ($i = 1; $i <= self::PARENTS; $i++) {
            $tags[] = ['id' => $i, 'name' => "Parent $i", 'slug' => "parent-$i", 'position' => $i, 'parent_id' => null];
            $tags[] = ['id' => 100 + $i, 'name' => "Child $i", 'slug' => "child-$i", 'position' => 0, 'parent_id' => $i];
            $discussions[] = ['id' => $i, 'title' => "Discussion $i", 'user_id' => 1, 'first_post_id' => $i, 'comment_count' => 1];
            $posts[] = ['id' => $i, 'discussion_id' => $i, 'user_id' => 1, 'type' => 'comment', 'number' => 1, 'content' => '<t><p>Content</p></t>'];
            $discussionTags[] = ['discussion_id' => $i, 'tag_id' => 100 + $i];
        }

        $this->prepareDatabase([
            User::class => [
                $this->normalUser(),
                ['id' => 3, 'username' => 'other', 'email' => 'other@machine.local', 'is_email_confirmed' => 1],
            ],
            Tag::class        => $tags,
            Discussion::class => $discussions,
            Post::class       => $posts,
            'discussion_tag'  => $discussionTags,
            'tag_user'        => [
                $this->state(2, 1, 'follow'),
                $this->state(2, 2, 'lurk'),
                $this->state(2, 103, 'ignore'),
                $this->state(3, 3, 'follow'),
                $this->state(3, 104, 'lurk'),
            ],
        ]);
    }

    /**
     * The subscriptions the member sees, keyed by tag ID.
     */
    protected function expectedForMember(array $ids): array
    {
        $subscriptions = array_fill_keys($ids, null);

        return array_replace($subscriptions, array_intersect_key([1 => 'follow', 2 => 'lurk', 103 => 'ignore'], $subscriptions));
    }

    #[Test]
    public function discussion_tag_parents_are_serialized_with_their_subscriptions()
    {
        $json = $this->get('/api/discussions', 2, ['include' => 'tags,tags.parent']);

        $subscriptions = $this->includedSubscriptions($json);
        $ids = array_keys($subscriptions);

        $this->assertCount(self::PARENTS * 2, $subscriptions);
        $this->assertEquals($this->expectedForMember($ids), $subscriptions);
    }

    #[Test]
    public function guests_see_no_subscriptions_on_discussion_tag_parents()
    {
        $json = $this->get('/api/discussions', null, ['include' => 'tags,tags.parent']);

        $subscriptions = $this->includedSubscriptions($json);

        $this->assertCount(self::PARENTS * 2, $subscriptions);
        $this->assertEquals(array_fill_keys(array_keys($subscriptions), null), $subscriptions);
    }

    #[Test]
    public function prompt_list_of_every_tag_loads_parents_together()
    {
        $this->setting('fof-follow-tags.prompt_button_on_following_page', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'all');

        $json = $this->get('/api', 2);

        $this->assertCount(self::PARENTS * 2, $json['data']['relationships']['fofFollowTagsPromptList']['data']);
        $this->assertParentsLinked($json);

        $subscriptions = $this->includedSubscriptions($json);
        $this->assertEquals($this->expectedForMember(array_keys($subscriptions)), $subscriptions);
    }

    #[Test]
    public function prompt_list_of_child_tags_includes_their_parents_subscriptions()
    {
        $children = range(101, 100 + self::PARENTS);

        $this->setting('fof-follow-tags.prompt_button_on_following_page', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'list');
        $this->setting('fof-follow-tags.prompt_tag_ids', json_encode($children));

        $json = $this->get('/api', 2);

        $listed = array_map('intval', array_column($json['data']['relationships']['fofFollowTagsPromptList']['data'], 'id'));
        sort($listed);
        $this->assertEquals($children, $listed);
        $this->assertParentsLinked($json);

        $subscriptions = $this->includedSubscriptions($json);
        $this->assertCount(self::PARENTS * 2, $subscriptions);
        $this->assertEquals($this->expectedForMember(array_keys($subscriptions)), $subscriptions);
    }

    #[Test]
    public function prompt_list_for_guests_loads_parents_together()
    {
        $this->setting('fof-follow-tags.prompt_button_on_following_page', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'all');

        $json = $this->get('/api', null);

        $this->assertCount(self::PARENTS * 2, $json['data']['relationships']['fofFollowTagsPromptList']['data']);
        $this->assertParentsLinked($json);
    }

    protected function get(string $path, ?int $userId, array $query = []): array
    {
        $request = $this->request('GET', $path, $userId ? ['authenticatedAs' => $userId] : []);

        $response = $this->send($request->withQueryParams($query));

        $this->assertEquals(200, $response->getStatusCode(), (string) $response->getBody());

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @return array<int, string|null> each included tag's subscription, by ID
     */
    protected function includedSubscriptions(array $json): array
    {
        $subscriptions = [];

        foreach ($json['included'] ?? [] as $resource) {
            if ($resource['type'] === 'tags') {
                $subscriptions[(int) $resource['id']] = $resource['attributes']['subscription'];
            }
        }

        ksort($subscriptions);

        return $subscriptions;
    }

    /**
     * Every included child tag links to its parent.
     */
    protected function assertParentsLinked(array $json): void
    {
        foreach ($json['included'] ?? [] as $resource) {
            if ($resource['type'] === 'tags' && $resource['id'] > 100) {
                $this->assertEquals((string) ($resource['id'] - 100), $resource['relationships']['parent']['data']['id'] ?? null);
            }
        }
    }

    protected function state(int $userId, int $tagId, string $subscription): array
    {
        return ['user_id' => $userId, 'tag_id' => $tagId, 'subscription' => $subscription, 'is_hidden' => 0];
    }
}

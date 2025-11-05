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

use Carbon\Carbon;
use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use ExtensionDepsTrait;
    use TagsDefinitionTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionDeps();

        $this->prepareDatabase([
            User::class => [
                $this->normalUser(),
            ],
            Tag::class => $this->tags(),
        ]);
    }

    #[Test]
    public function regular_user_can_follow_a_tag()
    {
        $response = $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'subscription' => 'follow',
                        ],
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals('follow', $data['data']['attributes']['subscription']);

        // Verify it's saved in the database
        $this->assertEquals(
            'follow',
            $this->database()->table('tag_user')
                ->where('user_id', 2)
                ->where('tag_id', 1)
                ->value('subscription')
        );
    }

    #[Test]
    public function regular_user_can_change_subscription()
    {
        // First, follow the tag
        $this->database()->table('tag_user')->insert([
            'user_id'      => 2,
            'tag_id'       => 1,
            'subscription' => 'follow',
            'created_at'   => Carbon::now(),
        ]);

        // Then change to lurk
        $response = $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'subscription' => 'lurk',
                        ],
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals('lurk', $data['data']['attributes']['subscription']);

        // Verify it's updated in the database
        $this->assertEquals(
            'lurk',
            $this->database()->table('tag_user')
                ->where('user_id', 2)
                ->where('tag_id', 1)
                ->value('subscription')
        );
    }

    #[Test]
    public function regular_user_can_unfollow_a_tag()
    {
        // First, follow the tag
        $this->database()->table('tag_user')->insert([
            'user_id'      => 2,
            'tag_id'       => 1,
            'subscription' => 'follow',
            'created_at'   => Carbon::now(),
        ]);

        // Then unfollow (set to null)
        $response = $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'subscription' => null,
                        ],
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertNull($data['data']['attributes']['subscription']);

        // Verify it's updated in the database
        $this->assertNull(
            $this->database()->table('tag_user')
                ->where('user_id', 2)
                ->where('tag_id', 1)
                ->value('subscription')
        );
    }

    #[Test]
    public function guest_cannot_follow_a_tag()
    {
        $response = $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'json' => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'subscription' => 'follow',
                        ],
                    ],
                ],
            ])
        );

        // Guest gets 400 because the field is not writable for them
        $this->assertEquals(400, $response->getStatusCode());
    }

    #[Test]
    public function guest_can_view_tags_but_subscription_is_null()
    {
        $response = $this->send(
            $this->request('GET', '/api/tags/1')
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        // Guest should be able to view tags
        $this->assertEquals('1', $data['data']['id']);
        $this->assertEquals('tags', $data['data']['type']);

        // Subscription field should be null for guests (they have no subscription)
        $this->assertNull($data['data']['attributes']['subscription']);
    }

    #[Test]
    public function regular_user_can_view_their_subscription_status()
    {
        // Set up a subscription
        $this->database()->table('tag_user')->insert([
            'user_id'      => 2,
            'tag_id'       => 1,
            'subscription' => 'follow',
            'created_at'   => Carbon::now(),
        ]);

        $response = $this->send(
            $this->request('GET', '/api/tags/1', [
                'authenticatedAs' => 2,
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        // User should see their subscription status
        $this->assertEquals('follow', $data['data']['attributes']['subscription']);
    }

    #[Test]
    public function regular_user_sees_null_subscription_when_not_following()
    {
        $response = $this->send(
            $this->request('GET', '/api/tags/1', [
                'authenticatedAs' => 2,
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        // User should see null when they haven't set a subscription
        $this->assertNull($data['data']['attributes']['subscription']);
    }
}

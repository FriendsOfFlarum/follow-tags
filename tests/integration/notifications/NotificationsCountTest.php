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
use Flarum\Notification\Notification;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;

class NotificationsCountTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use ExtensionDepsTrait;
    use TagsDefinitionTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionDeps();

        $this->prepareDatabase([
            'users' => [
                $this->normalUser(),
            ],
            'tags'     => $this->tags(),
            'tag_user' => [
                ['user_id' => 2, 'tag_id' => 1, 'is_hidden' => 0, 'subscription' => 'follow', 'created_at' => Carbon::now()->toDateTimeString()],
                ['user_id' => 2, 'tag_id' => 5, 'is_hidden' => 0, 'subscription' => 'follow', 'created_at' => Carbon::now()->toDateTimeString()],
                ['user_id' => 2, 'tag_id' => 2, 'is_hidden' => 0, 'subscription' => 'lurk', 'created_at' => Carbon::now()->toDateTimeString()],
                ['user_id' => 2, 'tag_id' => 6, 'is_hidden' => 0, 'subscription' => 'lurk', 'created_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussion_tag' => [
                ['discussion_id' => 1, 'tag_id' => 2, 'created_at' => Carbon::now()->toDateTimeString()],
                ['discussion_id' => 1, 'tag_id' => 6, 'created_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussion_user' => [
                ['user_id' => 2, 'discussion_id' => 1, 'last_read_post_number' => 1, 'last_read_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussions' => [
                ['id' => 1, 'title' => 'The quick brown fox jumps over the lazy dog', 'created_at' => Carbon::now()->toDateTimeString(), 'user_id' => 2, 'participant_count' => 1],
            ],
            'posts' => [
                ['id' => 1, 'discussion_id' => 1, 'user_id' => 2, 'type' => 'comment', 'content' => '<t><p>Following</p></t>', 'is_private' => 0, 'number' => 1],
            ],
        ]);
    }

    /**
     * @test
     */
    public function single_notification_sent_when_following_tag_and_subtag()
    {
        $response = $this->send(
            $this->request('POST', '/api/discussions', [
                'authenticatedAs' => 1,
                'json'            => [
                    'data' => [
                        'attributes' => [
                            'title'   => 'New discussion',
                            'content' => '<t><p>New Post</p></t>',
                        ],
                        'relationships' => [
                            'tags' => [
                                'data' => [
                                    ['type' => 'tags', 'id' => 1],
                                    ['type' => 'tags', 'id' => 5],
                                ],
                            ],
                        ],
                    ],
                ],
            ])
        );

        $this->assertEquals(201, $response->getStatusCode());

        $notificationRecipient = 2;

        $response = $this->send(
            $this->request('GET', '/api/notifications', [
                'authenticatedAs' => $notificationRecipient,
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getBody(), true);

        $this->assertEquals(1, count($response['data']));
        $this->assertEquals('newDiscussionInTag', $response['data'][0]['attributes']['contentType']);
        $this->assertEquals(1, User::query()->find($notificationRecipient)->notifications()->count());
        $this->assertEquals(1, Notification::query()->count());
        $this->assertEquals(1, Notification::query()->first()->from_user_id);
        $this->assertEquals(2, Notification::query()->first()->user_id);
    }

    /**
     * @test
     */
    public function single_notification_sent_when_lurking_tag_and_subtag()
    {
        $response = $this->send(
            $this->request('POST', '/api/posts', [
                'authenticatedAs' => 1,
                'json'            => [
                    'data' => [
                        'attributes' => [
                            'content' => '<t><p>New Post</p></t>',
                        ],
                        'relationships' => [
                            'discussion' => [
                                'data' => [
                                    'type' => 'discussions',
                                    'id'   => 1,
                                ],
                            ],
                        ],
                    ],
                ],
            ])
        );

        $this->assertEquals(201, $response->getStatusCode());

        $notificationRecipient = 2;

        $response = $this->send(
            $this->request('GET', '/api/notifications', [
                'authenticatedAs' => $notificationRecipient,
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getBody(), true);

        $this->assertEquals(1, count($response['data']));
        $this->assertEquals('newPostInTag', $response['data'][0]['attributes']['contentType']);
        $this->assertEquals(1, User::query()->find($notificationRecipient)->notifications()->count());
        $this->assertEquals(1, Notification::query()->count());
        $this->assertEquals(1, Notification::query()->first()->from_user_id);
        $this->assertEquals(2, Notification::query()->first()->user_id);
    }

    /**
     * @test
     */
    public function single_notification_sent_when_following_tag_and_subtag_and_discussion_retagged()
    {
        $response = $this->send(
            $this->request('PATCH', '/api/discussions/1', [
                'authenticatedAs' => 1,
                'json'            => [
                    'data' => [
                        'attributes'    => [],
                        'relationships' => [
                            'tags' => [
                                'data' => [
                                    ['type' => 'tags', 'id' => 1],
                                    ['type' => 'tags', 'id' => 5],
                                ],
                            ],
                        ],
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $notificationRecipient = 2;

        $response = $this->send(
            $this->request('GET', '/api/notifications', [
                'authenticatedAs' => $notificationRecipient,
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getBody(), true);

        $this->assertEquals(1, count($response['data']));
        $this->assertEquals('newDiscussionTag', $response['data'][0]['attributes']['contentType']);

        $this->assertEquals(1, User::query()->find($notificationRecipient)->notifications()->count());
        $this->assertEquals(1, Notification::query()->count());
        $this->assertEquals(1, Notification::query()->first()->from_user_id);
        $this->assertEquals(2, Notification::query()->first()->user_id);
    }
}

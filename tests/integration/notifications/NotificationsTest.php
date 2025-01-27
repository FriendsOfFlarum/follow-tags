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

class NotificationsTest extends TestCase
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
                ['user_id' => 2, 'tag_id' => 2, 'is_hidden' => 0, 'subscription' => 'lurk', 'created_at' => Carbon::now()->toDateTimeString()],
                ['user_id' => 2, 'tag_id' => 3, 'is_hidden' => 0, 'subscription' => 'ignore', 'created_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussion_tag' => [
                ['discussion_id' => 1, 'tag_id' => 1, 'created_at' => Carbon::now()->toDateTimeString()],
                ['discussion_id' => 2, 'tag_id' => 2, 'created_at' => Carbon::now()->toDateTimeString()],
                ['discussion_id' => 3, 'tag_id' => 3, 'created_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussion_user' => [
                ['user_id' => 2, 'discussion_id' => 1, 'last_read_post_number' => 1, 'last_read_at' => Carbon::now()->toDateTimeString()],
                ['user_id' => 2, 'discussion_id' => 2, 'last_read_post_number' => 1, 'last_read_at' => Carbon::now()->toDateTimeString()],
                ['user_id' => 2, 'discussion_id' => 3, 'last_read_post_number' => 1, 'last_read_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussions' => [
                ['id' => 1, 'title' => 'The quick brown fox jumps over the lazy dog', 'created_at' => Carbon::now()->toDateTimeString(), 'user_id' => 2, 'participant_count' => 1],
                ['id' => 2, 'title' => 'The quick brown fox jumps over the lazy dog', 'created_at' => Carbon::now()->toDateTimeString(), 'user_id' => 2, 'participant_count' => 1],
                ['id' => 3, 'title' => 'The quick brown fox jumps over the lazy dog', 'created_at' => Carbon::now()->toDateTimeString(), 'user_id' => 2, 'participant_count' => 1],
            ],
            'posts' => [
                ['id' => 1, 'discussion_id' => 1, 'user_id' => 2, 'type' => 'comment', 'content' => '<t><p>Following</p></t>', 'is_private' => 0, 'number' => 1],
                ['id' => 2, 'discussion_id' => 2, 'user_id' => 2, 'type' => 'comment', 'content' => '<t><p>Lurking</p></t>', 'is_private' => 0, 'number' => 1],
                ['id' => 3, 'discussion_id' => 3, 'user_id' => 2, 'type' => 'comment', 'content' => '<t><p>Ignoring</p></t>', 'is_private' => 0, 'number' => 1],
            ],
        ]);
    }

    /**
     * @test
     */
    public function notification_sent_when_new_discussion_in_followed_tag()
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
    public function no_notification_sent_when_new_post_in_followed_tag()
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

        $this->assertEquals(0, count($response['data']));
        $this->assertEquals(0, User::query()->find($notificationRecipient)->notifications()->count());
        $this->assertEquals(0, Notification::query()->count());
    }

    /**
     * @test
     */
    public function notification_sent_when_new_discussion_in_lurked_tag()
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
                                    ['type' => 'tags', 'id' => 2],
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
    public function notification_sent_when_new_post_in_lurked_tag()
    {
        /**
         * Please notice that a notification is only sent when the user
         * has read the last post in the discussion which is in a lurking tag.
         *
         * See `last_read_post_number` in the `discussion_user` table.
         *
         * Users won't receive notifications for posts in discussions they haven't read,
         * even if the discussion is in a tag, they lurk.
         */
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
                                    'id'   => 2,
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
    public function no_notification_sent_when_new_post_mention_in_ignored_tag()
    {
        $response = $this->send(
            $this->request('POST', '/api/posts', [
                'authenticatedAs' => 1,
                'json'            => [
                    'data' => [
                        'attributes' => [
                            'content' => '@"NORMAL$"#p3',
                        ],
                        'relationships' => [
                            'discussion' => [
                                'data' => [
                                    'type' => 'discussions',
                                    'id'   => 3,
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

        $this->assertEquals(0, count($response['data']));
        $this->assertEquals(0, User::query()->find($notificationRecipient)->notifications()->count());
        $this->assertEquals(0, Notification::query()->count());
    }

    /**
     * @test
     */
    public function no_notification_sent_when_new_user_mention_in_ignored_tag()
    {
        $response = $this->send(
            $this->request('POST', '/api/posts', [
                'authenticatedAs' => 1,
                'json'            => [
                    'data' => [
                        'attributes' => [
                            'content' => '@normal',
                        ],
                        'relationships' => [
                            'discussion' => [
                                'data' => [
                                    'type' => 'discussions',
                                    'id'   => 3,
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

        $this->assertEquals(0, count($response['data']));
        $this->assertEquals(0, User::query()->find($notificationRecipient)->notifications()->count());
        $this->assertEquals(0, Notification::query()->count());
    }

    /**
     * @test
     */
    public function notification_sent_when_discussion_retagged_to_accessible_tag()
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
                                    ['type' => 'tags', 'id' => 2],
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

    /**
     * @test
     */
    public function no_notification_sent_when_discussion_retagged_to_restricted_tag()
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
                                    ['type' => 'tags', 'id' => 4],
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

        $this->assertEquals(0, count($response['data']));
        $this->assertEquals(0, User::query()->find($notificationRecipient)->notifications()->count());
        $this->assertEquals(0, Notification::query()->count());
    }
}

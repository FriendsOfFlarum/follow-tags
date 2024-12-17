<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\tests\integration\notifications;

use Carbon\Carbon;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use FoF\FollowTags\tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\tests\integration\TagsDefinitionTrait;

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
                ['user_id' => 2, 'tag_id' => 2, 'is_hidden' => 0, 'subscription' => 'lurking', 'created_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussion_tag' => [
                ['discussion_id' => 1, 'tag_id' => 1, 'created_at' => Carbon::now()->toDateTimeString()],
                ['discussion_id' => 2, 'tag_id' => 2, 'created_at' => Carbon::now()->toDateTimeString()],
            ],
            'discussions' => [
                ['id' => 1, 'title' => 'The quick brown fox jumps over the lazy dog', 'created_at' => Carbon::now()->toDateTimeString(), 'user_id' => 2, 'participant_count' => 1],
                ['id' => 2, 'title' => 'The quick brown fox jumps over the lazy dog', 'created_at' => Carbon::now()->toDateTimeString(), 'user_id' => 2, 'participant_count' => 1],
            ],
            'posts' => [
                ['id' => 1, 'discussion_id' => 1, 'user_id' => 2, 'type' => 'comment', 'content' => '<t><p>Following</p></t>', 'is_private' => 0, 'is_approved' => 1, 'number' => 1],
                ['id' => 2, 'discussion_id' => 2, 'user_id' => 2, 'type' => 'comment', 'content' => '<t><p>Lurking</p></t>', 'is_private' => 0, 'is_approved' => 1, 'number' => 1],
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
                'json' => [
                    'data' => [
                        'attributes' => [
                            'title' => 'New discussion',
                            'content' => '<t><p>New Post</p></t>',
                        ],
                        'relationships' => [
                            'tags' => [
                                'data' => [
                                    ['type' => 'tags', 'id' => 1]
                                ]
                            ]
                        ]
                    ],
                ],
            ])
        );

        $this->assertEquals(201, $response->getStatusCode());

        $response = $this->send(
            $this->request('GET', '/api/notifications', [
                'authenticatedAs' => 2,
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getBody(), true);

        $this->assertEquals(1, count($response['data']));
        $this->assertEquals('newDiscussionInTag', $response['data'][0]['attributes']['contentType']);
    }

    /**
     * @test
     */
    public function no_notification_sent_when_new_post_in_followed_tag()
    {
        
    }

    /**
     * @test
    */
    public function notification_sent_when_new_discussion_in_lurked_tag()
    {

    }

    /**
     * @test
    */
    public function notification_sent_when_new_post_in_lurked_tag()
    {
        
    }
}

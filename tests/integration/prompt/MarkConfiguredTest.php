<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Tests\integration\prompt;

use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;
use PHPUnit\Framework\Attributes\Test;

class MarkConfiguredTest extends TestCase
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
                array_merge($this->normalUser(), [
                    'id'                                    => 3,
                    'username'                              => 'configured_user',
                    'email'                                 => 'configured@machine.local',
                    'fof_follow_tags_prompt_configured_at'  => '2025-01-01 00:00:00',
                ]),
            ],
            Tag::class => $this->tags(),
        ]);
    }

    protected function markConfigured(int $targetUserId, int $actorId)
    {
        return $this->send(
            $this->request('PATCH', "/api/users/$targetUserId", [
                'authenticatedAs' => $actorId,
                'json'            => [
                    'data' => [
                        'type'       => 'users',
                        'id'         => (string) $targetUserId,
                        'attributes' => [
                            'fofFollowTagsPromptConfigured' => true,
                        ],
                    ],
                ],
            ])
        );
    }

    #[Test]
    public function user_can_mark_themselves_as_configured()
    {
        $response = $this->markConfigured(2, 2);

        $this->assertEquals(200, $response->getStatusCode());

        /** @var User $user */
        $user = User::query()->find(2);

        $this->assertNotNull($user->fof_follow_tags_prompt_configured_at);
    }

    #[Test]
    public function user_cannot_mark_another_user_as_configured()
    {
        $response = $this->markConfigured(2, 3);

        $this->assertEquals(403, $response->getStatusCode());

        /** @var User $user */
        $user = User::query()->find(2);

        $this->assertNull($user->fof_follow_tags_prompt_configured_at);
    }

    #[Test]
    public function admin_cannot_mark_another_user_as_configured()
    {
        $response = $this->markConfigured(2, 1);

        $this->assertEquals(403, $response->getStatusCode());

        /** @var User $user */
        $user = User::query()->find(2);

        $this->assertNull($user->fof_follow_tags_prompt_configured_at);
    }

    #[Test]
    public function existing_configured_timestamp_is_not_overwritten()
    {
        $response = $this->markConfigured(3, 3);

        $this->assertEquals(200, $response->getStatusCode());

        /** @var User $user */
        $user = User::query()->find(3);

        $this->assertEquals('2025-01-01 00:00:00', $user->fof_follow_tags_prompt_configured_at->toDateTimeString());
    }
}

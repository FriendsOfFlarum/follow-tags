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
}

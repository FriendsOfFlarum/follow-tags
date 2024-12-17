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

use Flarum\Testing\integration\TestCase;
use FoF\FollowTags\tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\tests\integration\TagsDefinitionTrait;

class NotificationsTest extends TestCase
{
    use ExtensionDepsTrait;
    use TagsDefinitionTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionDeps();

        $this->prepareDatabase([
            'tags' => $this->tags(),
        ]);
    }
}

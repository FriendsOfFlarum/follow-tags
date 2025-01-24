<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Tests\integration;

trait TagsDefinitionTrait
{
    public function tags(): array
    {
        return [
            ['id' => 1, 'name' => 'General', 'slug' => 'general', 'position' => 0, 'parent_id' => null],
            ['id' => 2, 'name' => 'Testing', 'slug' => 'testing', 'position' => 1, 'parent_id' => null],
            ['id' => 3, 'name' => 'Playground', 'slug' => 'playground', 'position' => 1, 'parent_id' => null],
            ['id' => 4, 'name' => 'Archive', 'slug' => 'archive', 'position' => 2, 'parent_id' => null, 'is_restricted' => true],
            ['id' => 5, 'name' => 'General Child', 'slug' => 'general-child', 'position' => 0, 'parent_id' => 1],
            ['id' => 6, 'name' => 'Testing Child', 'slug' => 'testing-child', 'position' => 0, 'parent_id' => 2],

        ];
    }
}

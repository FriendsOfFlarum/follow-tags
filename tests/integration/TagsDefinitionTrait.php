<?php

namespace FoF\FollowTags\tests\integration;

trait TagsDefinitionTrait
{
    public function tags(): array
    {
        return [
            ['id' => 1, 'name' => 'General', 'slug' => 'general', 'position' => 0, 'parent_id' => null],
            ['id' => 2, 'name' => 'Testing', 'slug' => 'testing', 'position' => 1, 'parent_id' => null],
            ['id' => 3, 'name' => 'Archive', 'slug' => 'archive', 'position' => 2, 'parent_id' => null, 'is_restricted' => true],
        ];
    }
}

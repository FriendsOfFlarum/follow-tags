<?php

namespace FoF\FollowTags\tests\integration;

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

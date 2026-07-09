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

class PromptTagListTest extends TestCase
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
            Tag::class => array_merge($this->tags(), [
                ['id' => 8, 'name' => 'Secondary', 'slug' => 'secondary', 'position' => null, 'parent_id' => null],
            ]),
        ]);
    }

    /**
     * @return int[]
     */
    protected function promptTagIds(?int $userId = null): array
    {
        $options = $userId ? ['authenticatedAs' => $userId] : [];

        $response = $this->send($this->request('GET', '/api', $options));

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $data = $json['data']['relationships']['fofFollowTagsPromptList']['data'] ?? [];

        $ids = array_map(function (array $identifier) {
            return (int) $identifier['id'];
        }, $data);

        sort($ids);

        return $ids;
    }

    #[Test]
    public function no_tags_are_loaded_when_prompt_is_disabled()
    {
        $this->assertEquals([], $this->promptTagIds(2));
    }

    #[Test]
    public function tags_are_loaded_when_only_the_following_page_button_is_enabled()
    {
        $this->setting('fof-follow-tags.prompt_button_on_following_page', '1');

        $this->assertEquals([1, 2, 3], $this->promptTagIds(2));
    }

    #[Test]
    public function default_strategy_is_first_level_primary_tags()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');

        $this->assertEquals([1, 2, 3], $this->promptTagIds(2));
    }

    #[Test]
    public function restricted_tags_are_included_for_actors_who_can_see_them()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');

        $this->assertEquals([1, 2, 3, 4, 7], $this->promptTagIds(1));
    }

    #[Test]
    public function primary_and_children_strategy()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'primaryAndChildren');

        $this->assertEquals([1, 2, 3, 5, 6], $this->promptTagIds(2));
    }

    #[Test]
    public function primary_and_secondary_strategy()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'primaryAndSecondary');

        $this->assertEquals([1, 2, 3, 8], $this->promptTagIds(2));
    }

    #[Test]
    public function secondary_strategy()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'secondary');

        $this->assertEquals([8], $this->promptTagIds(2));
    }

    #[Test]
    public function all_strategy()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'all');

        $this->assertEquals([1, 2, 3, 5, 6, 8], $this->promptTagIds(2));
    }

    #[Test]
    public function list_strategy_uses_selected_tag_ids()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');
        $this->setting('fof-follow-tags.prompt_tag_strategy', 'list');
        $this->setting('fof-follow-tags.prompt_tag_ids', '[1,3]');

        $this->assertEquals([1, 3], $this->promptTagIds(2));
    }
}

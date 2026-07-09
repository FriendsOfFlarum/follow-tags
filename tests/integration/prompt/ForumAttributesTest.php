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

class ForumAttributesTest extends TestCase
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

    protected function forumAttributes(?int $userId = null): array
    {
        $options = $userId ? ['authenticatedAs' => $userId] : [];

        $response = $this->send($this->request('GET', '/api', $options));

        $this->assertEquals(200, $response->getStatusCode());

        return json_decode($response->getBody()->getContents(), true)['data']['attributes'];
    }

    #[Test]
    public function prompt_is_disabled_by_default()
    {
        $attributes = $this->forumAttributes(2);

        $this->assertFalse($attributes['fofFollowTagsPromptShouldPrompt']);
        $this->assertFalse($attributes['fofFollowTagsPromptButton']);
    }

    #[Test]
    public function prompt_shown_to_unconfigured_user_when_enabled()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');

        $attributes = $this->forumAttributes(2);

        $this->assertTrue($attributes['fofFollowTagsPromptShouldPrompt']);
    }

    #[Test]
    public function following_page_button_works_independently_of_the_prompt()
    {
        $this->setting('fof-follow-tags.prompt_button_on_following_page', '1');

        $attributes = $this->forumAttributes(2);

        $this->assertTrue($attributes['fofFollowTagsPromptButton']);
        $this->assertFalse($attributes['fofFollowTagsPromptShouldPrompt']);
    }

    #[Test]
    public function prompt_not_shown_to_guest_when_enabled()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');

        $attributes = $this->forumAttributes();

        $this->assertFalse($attributes['fofFollowTagsPromptShouldPrompt']);
    }

    #[Test]
    public function prompt_not_shown_to_user_who_already_configured()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');

        $attributes = $this->forumAttributes(3);

        $this->assertFalse($attributes['fofFollowTagsPromptShouldPrompt']);
    }

    #[Test]
    public function following_page_button_is_disabled_by_default_even_when_prompt_is_on()
    {
        $this->setting('fof-follow-tags.prompt_new_users', '1');

        $attributes = $this->forumAttributes(2);

        $this->assertFalse($attributes['fofFollowTagsPromptButton']);
    }

    #[Test]
    public function all_discussions_for_guests_is_disabled_by_default()
    {
        $attributes = $this->forumAttributes();

        $this->assertFalse($attributes['fofFollowTagsAllDiscussionsForGuests']);
    }

    #[Test]
    public function all_discussions_for_guests_reflects_setting()
    {
        $this->setting('fof-follow-tags.all_discussions_on_following_page_for_guests', '1');

        $attributes = $this->forumAttributes();

        $this->assertTrue($attributes['fofFollowTagsAllDiscussionsForGuests']);
    }
}

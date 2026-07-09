<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags;

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Settings\SettingsRepositoryInterface;

class AddForumPromptAttributes
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function __invoke(ForumSerializer $serializer): array
    {
        $actor = $serializer->getActor();

        $enabled = $this->settings->get('fof-follow-tags.prompt_new_users') === '1';

        return [
            'fofFollowTagsPromptShouldPrompt'      => $enabled && $actor->exists && is_null($actor->fof_follow_tags_prompt_configured_at),
            'fofFollowTagsPromptButton'            => $this->settings->get('fof-follow-tags.prompt_button_on_following_page') === '1',
            'fofFollowTagsAllDiscussionsForGuests' => $this->settings->get('fof-follow-tags.all_discussions_on_following_page_for_guests') === '1',
        ];
    }
}

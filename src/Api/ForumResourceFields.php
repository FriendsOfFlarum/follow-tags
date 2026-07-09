<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Api;

use Flarum\Api\Context;
use Flarum\Api\Schema;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Tags\Tag;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class ForumResourceFields
{
    public function __construct(
        protected SettingsRepositoryInterface $settings
    ) {
    }

    public function __invoke(): array
    {
        return [
            Schema\Boolean::make('fofFollowTagsPromptShouldPrompt')
                ->get(function (object $forum, Context $context) {
                    $actor = $context->getActor();

                    return $this->promptEnabled() && $actor->exists && is_null($actor->fof_follow_tags_prompt_configured_at);
                }),

            Schema\Boolean::make('fofFollowTagsPromptButton')
                ->get(fn () => $this->buttonEnabled()),

            Schema\Boolean::make('fofFollowTagsAllDiscussionsForGuests')
                ->get(fn () => $this->settings->get('fof-follow-tags.all_discussions_on_following_page_for_guests') === '1'),

            Schema\Relationship\ToMany::make('fofFollowTagsPromptList')
                ->type('tags')
                ->includable()
                ->get(function (object $forum, Context $context) {
                    // The tag list is only needed when the modal can be opened, either
                    // automatically or through the button on the Following page
                    if (!$this->promptEnabled() && !$this->buttonEnabled()) {
                        return [];
                    }

                    $actor = $context->getActor();

                    return $this->query($actor)->get()->all();
                }),
        ];
    }

    protected function promptEnabled(): bool
    {
        return $this->settings->get('fof-follow-tags.prompt_new_users') === '1';
    }

    protected function buttonEnabled(): bool
    {
        return $this->settings->get('fof-follow-tags.prompt_button_on_following_page') === '1';
    }

    protected function query(User $actor): Builder
    {
        /** @var Builder $query */
        $query = Tag::query()->withStateFor($actor)->whereVisibleTo($actor);

        $strategy = $this->settings->get('fof-follow-tags.prompt_tag_strategy') ?: 'primary';

        switch ($strategy) {
            case 'primary':
                return $query->where(function (Builder $query) {
                    $query
                        ->whereNull('parent_id')
                        ->whereNotNull('position');
                });
            case 'primaryAndChildren':
                return $query->where(function (Builder $query) {
                    $query
                        ->whereNotNull('parent_id')
                        ->orWhereNotNull('position');
                });
            case 'primaryAndSecondary':
                return $query->whereNull('parent_id');
            case 'secondary':
                return $query->whereNull('position');
            case 'list':
                $ids = json_decode($this->settings->get('fof-follow-tags.prompt_tag_ids', '[]'));

                return $query->whereIn('id', is_array($ids) ? $ids : []);
        }

        // case 'all'
        return $query;
    }
}

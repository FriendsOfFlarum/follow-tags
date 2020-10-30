<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Mentions\Notification\PostMentionedBlueprint;
use Flarum\Mentions\Notification\UserMentionedBlueprint;
use Flarum\Notification\Event\Sending;
use Flarum\Tags\TagState;

class PreventMentionNotificationsFromIgnoredTags
{
    public function handle(Sending $event)
    {
        $blueprint = $event->blueprint;

        if (!($blueprint instanceof PostMentionedBlueprint || $blueprint instanceof UserMentionedBlueprint)) {
            return;
        }

        /**
         * @var PostMentionedBlueprint|UserMentionedBlueprint
         * @var $ids                                          \Illuminate\Support\Collection
         * @var $tags                                         \Illuminate\Support\Collection
         */
        $ids = collect($event->users)->pluck('id');
        $tags = $blueprint->post->discussion->tags->pluck('id');

        if ($tags->isEmpty()) {
            return;
        }

        $ids = TagState::whereIn('tag_id', $tags)
            ->whereIn('user_id', $ids)
            ->where('subscription', 'hide')
            ->pluck('user_id');

        if ($ids->isEmpty()) {
            return;
        }

        $event->users = array_filter($event->users, function ($user) use ($ids) {
            return !$ids->contains($user->id);
        });
    }
}

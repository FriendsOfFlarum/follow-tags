<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Mentions\Notification\PostMentionedBlueprint;
use Flarum\Mentions\Notification\UserMentionedBlueprint;
use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Tags\TagState;

class PreventMentionNotificationsFromIgnoredTags
{
    public function __invoke(BlueprintInterface $blueprint, array $recipients): array
    {
        if (!($blueprint instanceof PostMentionedBlueprint || $blueprint instanceof UserMentionedBlueprint)) {
            return $recipients;
        }

        /**
         * @var PostMentionedBlueprint|UserMentionedBlueprint
         * @var $ids                                          \Illuminate\Support\Collection
         * @var $tags                                         \Illuminate\Support\Collection
         */
        $ids = collect($recipients)->pluck('id');
        $tags = $blueprint->post->discussion->tags->pluck('id');

        if ($tags->isEmpty()) {
            return $recipients;
        }

        $ids = TagState::whereIn('tag_id', $tags)
            ->whereIn('user_id', $ids)
            ->where('subscription', 'hide')
            ->pluck('user_id');

        if ($ids->isEmpty()) {
            return $recipients;
        }

        return array_filter($recipients, function ($user) use ($ids) {
            return !$ids->contains($user->id);
        });
    }
}

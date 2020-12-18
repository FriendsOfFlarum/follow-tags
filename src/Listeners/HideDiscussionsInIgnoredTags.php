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

use Flarum\Discussion\Event\Searching;
use Flarum\Tags\TagState;

class HideDiscussionsInIgnoredTags
{
    public function handle(Searching $event)
    {
        if (count($event->search->getActiveGambits()) > 0) {
            return;
        }

        $user = $event->criteria->actor;
        $event->search->getQuery()
            ->whereNotIn('discussions.id', function ($query) use ($user) {
                return $query->select('discussion_id')
                    ->from('discussion_tag')
                    ->whereIn('tag_id', function ($query) use ($user) {
                        $query
                            ->select('tag_id')
                            ->from((new TagState())->getTable())
                            ->where('user_id', $user->id)
                            ->where('subscription', 'hide');
                    });
            });
    }
}

<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2019 FriendsOfFlarum.
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

        $db = $event->search->getQuery()->getConnection();

        $event->search->getQuery()
            ->whereNotExists(function ($query) use ($db, $user) {
                return $query->selectRaw('1')
                    ->from('discussion_tag')
                    ->whereIn('tag_id', function ($query) use ($db, $user) {
                        $query
                            ->select($db->raw(1))
                            ->from((new TagState())->getTable())
                            ->where('user_id', $user->id)
                            ->where('subscription', 'hide');
                    })
                    ->whereColumn('discussions.id', 'discussion_id');
            });
    }
}

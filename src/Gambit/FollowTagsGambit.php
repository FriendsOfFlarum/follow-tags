<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Gambit;

use Flarum\Search\AbstractRegexGambit;
use Flarum\Search\AbstractSearch;
use Flarum\Tags\TagState;

class FollowTagsGambit extends AbstractRegexGambit
{
    /**
     * {@inheritdoc}
     */
    protected $pattern = 'is:following-tag';

    protected function conditions(AbstractSearch $search, array $matches, $negate)
    {
        $actor = $search->getActor();

        if ($actor->isGuest()) {
            return;
        }

        $search->getQuery()->whereExists(function ($query) use ($actor) {
            $tagIds = TagState::query()
                ->where('user_id', $actor->id)
                ->whereIn('subscription', ['lurk', 'follow'])
                ->pluck('tag_id')
                ->all();

            $query->selectRaw('1')
                ->from('discussion_tag')
                ->whereIn('tag_id', $tagIds)
                ->whereColumn('discussions.id', 'discussion_id');
        });
    }
}

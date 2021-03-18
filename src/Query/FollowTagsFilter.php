<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Query;

use Flarum\Filter\FilterInterface;
use Flarum\Filter\FilterState;
use Flarum\Tags\TagState;
use Flarum\User\User;
use Illuminate\Database\Query\Builder;

class FollowTagsFilter implements FilterInterface
{
    public function getFilterKey(): string
    {
        return 'following-tag';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        $this->constrain($filterState->getQuery(), $filterState->getActor(), $negate);
    }

    protected function constrain(Builder $query, User $actor, bool $negate)
    {
        if ($actor->isGuest()) {
            return;
        }

        $tagIds = TagState::query()
            ->where('user_id', $actor->id)
            ->whereIn('subscription', ['lurk', 'follow'])
            ->pluck('tag_id')
            ->all();

        // TODO: errors with flarum.discussions table not found
        $query->selectRaw('1')
            ->from('discussion_tag')
            ->whereIn('tag_id', $tagIds)
            ->whereColumn('discussions.id', 'discussion_id');

        // alterntive
        // TODO: errors with tag_id column not found
        $query->where(function (Builder $query) use ($tagIds, $negate) {
            if (!$negate) {
                $query->selectRaw('1')
                    ->from('discussion_tag')
                    ->whereIn('tag_id', $tagIds)
                    ->whereColumn('discussions.id', 'discussion_id');
            } else {
                // TODO: implement negation
            }
        });
    }
}

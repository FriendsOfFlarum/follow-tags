<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Search;

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

        $method = $negate ? 'whereNotIn' : 'whereIn';

        $query->$method('discussions.id', function ($query) use ($actor) {
            $query->select('discussion_id')
                ->from('discussion_tag')
                ->whereIn('tag_id', function ($query) use ($actor) {
                    $query->select('tag_id')
                        ->from((new TagState())->getTable())
                        ->where('user_id', $actor->id)
                        ->whereIn('subscription', ['lurk', 'follow']);
                });
        });
    }
}

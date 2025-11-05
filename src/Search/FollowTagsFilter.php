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

use Flarum\Search\Filter\FilterInterface;
use Flarum\Search\SearchState;
use Flarum\Tags\TagState;
use Flarum\User\User;
use Illuminate\Database\Query\Builder;

class FollowTagsFilter implements FilterInterface
{
    public function getFilterKey(): string
    {
        return 'following-tag';
    }

    public function filter(SearchState $state, array|string $value, bool $negate): void
    {
        $this->constrain($state->getQuery(), $state->getActor(), $negate);
    }

    protected function constrain(\Illuminate\Database\Eloquent\Builder $query, User $actor, bool $negate): void
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

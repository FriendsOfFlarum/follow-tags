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

use Flarum\Filter\FilterState;
use Flarum\Query\QueryCriteria;
use Flarum\Tags\TagState;

class HideTagsFilter
{
    public function __invoke(FilterState $state, QueryCriteria $criteria)
    {
        $actor = $state->getActor();

        if ($actor->isGuest() || array_key_exists('tag', $criteria->query)) {
            return;
        }

        $state->getQuery()->whereNotIn('discussions.id', function ($query) use ($actor) {
            $query->select('discussion_id')
                ->from('discussion_tag')
                ->whereIn('tag_id', function ($query) use ($actor) {
                    $query->select('tag_id')
                        ->from((new TagState())->getTable())
                        ->where('user_id', $actor->id)
                        ->where('subscription', 'hide');
                });
        });
    }
}

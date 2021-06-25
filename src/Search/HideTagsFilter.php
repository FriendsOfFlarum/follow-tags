<?php

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

        $state->getQuery()->whereNotIn('id', function ($query) use ($actor) {
            $query->select('discussion_id')
                ->from('discussion_tag')
                ->whereIn('tag_id', function ($query) use ($actor) {
                    $query->select('tag_id')
                        ->from((new TagState)->getTable())
                        ->where('user_id', $actor->id)
                        ->where('subscription', 'hide');
                });
        });
    }
}

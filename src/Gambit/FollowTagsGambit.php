<?php


namespace FoF\FollowTags\Gambit;


use Flarum\Search\AbstractRegexGambit;
use Flarum\Search\AbstractSearch;
use Flarum\Subscriptions\Gambit\SubscriptionGambit;
use Flarum\Tags\TagState;
use Illuminate\Support\Arr;

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

        $includeFollowing = Arr::first($search->getActiveGambits(), function ($gambit) {
            return $gambit instanceof SubscriptionGambit;
        });

        $search->getQuery()->{$includeFollowing ? 'orWhereExists' : 'whereExists'}(function ($query) use ($actor, $negate) {
            $tagIds = TagState::query()
                ->where('user_id', $actor->id)
                ->whereIn('subscription', ['lurk', 'follow'])
                ->pluck('tag_id')
                ->all();

            return $query->selectRaw('1')
                ->from('discussion_tag')
                ->whereIn('tag_id', $tagIds)
                ->whereColumn('discussions.id', 'discussion_id');
        });
    }
}

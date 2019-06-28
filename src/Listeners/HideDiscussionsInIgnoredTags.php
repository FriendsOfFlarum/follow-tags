<?php


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
            ->whereNotExists(function ($query) use ($user) {
                $hide = TagState::where('user_id', $user->id)->where('subscription', 'hide')->pluck('tag_id');

                return $query->selectRaw('1')
                    ->from('discussion_tag')
                    ->whereIn('tag_id', $hide)
                    ->whereColumn('discussions.id', 'discussion_id');
            });
    }
}
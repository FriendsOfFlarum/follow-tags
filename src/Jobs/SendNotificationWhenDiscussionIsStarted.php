<?php


namespace FoF\FollowTags\Jobs;


use Flarum\Discussion\Discussion;
use Flarum\Notification\NotificationSyncer;
use Flarum\Tags\Tag;
use Flarum\User\User;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendNotificationWhenDiscussionIsStarted implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Discussion
     */
    protected $discussion;

    public function __construct(Discussion $discussion)
    {
        $this->discussion = $discussion;
    }

    public function handle(NotificationSyncer $notifications) {
        /**
         * @var Collection
         * @var $tagIds    Collection
         */
        $tags = $this->discussion->tags;
        $tagIds = $tags->map->id;

        if ($tags->isEmpty()) {
            return;
        }

        $notify = User::where('users.id', '!=', $this->discussion->user_id)
            ->join('tag_user', 'tag_user.user_id', '=', 'users.id')
            ->whereIn('tag_user.tag_id', $tagIds->all())
            ->whereIn('tag_user.subscription', ['follow', 'lurk'])
            ->get()
            ->reject(function ($user) use ($tags) {
                return $tags->map->state->filter()->map->subscription->contains('ignore')
                    || $tags->whereIn('id', Tag::getIdsWhereCannot($user, 'viewDiscussions'))->isNotEmpty();
            });

        $notifications->sync(
            new NewDiscussionBlueprint($this->discussion),
            $notify->all()
        );
    }
}

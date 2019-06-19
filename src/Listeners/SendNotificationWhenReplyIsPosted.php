<?php


namespace FoF\FollowTags\Listeners;


use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Event\Posted;
use Flarum\Tags\Tag;
use FoF\FollowTags\Notifications\NewPostBlueprint;
use Illuminate\Support\Collection;

class SendNotificationWhenReplyIsPosted
{
    /**
     * @var NotificationSyncer
     */
    protected $notifications;

    /**
     * @param NotificationSyncer $notifications
     */
    public function __construct(NotificationSyncer $notifications)
    {
        $this->notifications = $notifications;
    }

    public function handle(Posted $event)
    {
        $post = $event->post;
        $discussion = $post->discussion;

        /**
         * @type $tags Collection
         * @type $tagIds Collection
         */
        $tags = $discussion->tags;
        $tagIds = $tags->map->id;

        if ($tags->isEmpty()) return;

        $notify = $discussion->readers()
            ->where('users.id', '!=', $post->user_id)
            ->join('tag_user', 'tag_user.user_id', '=', 'users.id')
            ->whereIn('tag_user.tag_id', $tagIds->all())
            ->where('tag_user.subscription', 'lurk')
            ->where('discussion_user.last_read_post_number', $discussion->last_post_number)
            ->get()
            ->reject(function ($user) use ($tags) {
                return $tags->map->state->map->subscription->contains('ignore')
                    || $tags->whereIn('id', Tag::getIdsWhereCannot($user, 'viewDiscussions'))->isNotEmpty();
            });

        $this->notifications->sync(
            new NewPostBlueprint($post),
            $notify->all()
        );
    }
}
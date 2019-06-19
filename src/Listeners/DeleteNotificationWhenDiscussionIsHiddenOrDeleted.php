<?php


namespace FoF\FollowTags\Listeners;


use Flarum\Discussion\Event\Deleted;
use Flarum\Discussion\Event\Hidden;
use Flarum\Notification\NotificationSyncer;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;

class DeleteNotificationWhenDiscussionIsHiddenOrDeleted
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

    /**
     * @param Hidden|Deleted $event
     */
    public function handle($event)
    {
        $this->notifications->delete(new NewDiscussionBlueprint($event->discussion));
    }
}
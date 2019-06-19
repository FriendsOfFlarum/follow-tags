<?php


namespace FoF\FollowTags\Listeners;


use Flarum\Notification\NotificationSyncer;
use Flarum\Discussion\Event\Restored;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;

class RestoreNotificationWhenDiscussionIsRestored
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

    public function handle(Restored $event)
    {
        $this->notifications->restore(new NewDiscussionBlueprint($event->discussion));
    }
}
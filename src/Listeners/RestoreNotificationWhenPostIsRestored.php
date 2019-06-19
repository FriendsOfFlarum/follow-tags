<?php


namespace FoF\FollowTags\Listeners;


use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Event\Restored;
use FoF\FollowTags\Notifications\NewPostBlueprint;

class RestoreNotificationWhenPostIsRestored
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
        $this->notifications->restore(new NewPostBlueprint($event->post));
    }
}
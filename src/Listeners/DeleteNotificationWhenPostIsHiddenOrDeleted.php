<?php


namespace FoF\FollowTags\Listeners;


use Flarum\Post\Event\Deleted;
use Flarum\Post\Event\Hidden;
use Flarum\Notification\NotificationSyncer;
use FoF\FollowTags\Notifications\NewPostBlueprint;

class DeleteNotificationWhenPostIsHiddenOrDeleted
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
        $this->notifications->delete(new NewPostBlueprint($event->post));
    }
}
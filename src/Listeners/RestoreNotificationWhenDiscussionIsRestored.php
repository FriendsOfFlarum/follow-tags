<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Discussion\Event\Restored;
use Flarum\Notification\NotificationSyncer;
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

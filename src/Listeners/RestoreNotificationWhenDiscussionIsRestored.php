<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Discussion\Event\Restored;
use Flarum\Notification\NotificationSyncer;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;

class RestoreNotificationWhenDiscussionIsRestored
{
    public function __construct(
        protected NotificationSyncer $notifications
    ) {
    }

    public function handle(Restored $event)
    {
        $this->notifications->restore(new NewDiscussionBlueprint($event->discussion));
    }
}

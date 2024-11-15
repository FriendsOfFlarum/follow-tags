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

use Flarum\Discussion\Event\Deleted;
use Flarum\Discussion\Event\Hidden;
use Flarum\Notification\NotificationSyncer;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;

class DeleteNotificationWhenDiscussionIsHiddenOrDeleted
{
    public function __construct(protected NotificationSyncer $notifications)
    {
    }

    /**
     * @param Hidden|Deleted $event
     */
    public function handle($event)
    {
        $this->notifications->delete(new NewDiscussionBlueprint($event->discussion));
    }
}

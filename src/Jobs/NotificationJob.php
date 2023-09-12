<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Jobs;

use Flarum\Database\Eloquent\Collection;
use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Notification\NotificationSyncer;
use Flarum\Queue\AbstractJob;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationJob extends AbstractJob implements ShouldQueue
{
    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * Sync the provided notifications.
     *
     * @param NotificationSyncer $syncer
     * @param BlueprintInterface $blueprint
     * @param Collection         $recipients
     */
    protected function sync(NotificationSyncer $syncer, BlueprintInterface $blueprint, Collection $recipients): void
    {
        $syncer->sync($blueprint, $recipients->all());
    }
}

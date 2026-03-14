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

use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Notification\NotificationSyncer;
use Flarum\Queue\AbstractJob;
use Flarum\Tags\TagState;
use Flarum\User\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

class NotificationJob extends AbstractJob implements ShouldQueue
{
    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * Sync the provided notifications.
     *
     * @param NotificationSyncer    $syncer
     * @param BlueprintInterface    $blueprint
     * @param Collection<int, User> $recipients
     */
    protected function sync(NotificationSyncer $syncer, BlueprintInterface $blueprint, Collection $recipients): void
    {
        /** @var array<User> $users */
        $users = $recipients->all();
        $syncer->sync($blueprint, $users);
    }

    /**
     * Pre-load all TagState rows for the given users and tags in a single query.
     * Returns a collection keyed by user_id for O(1) lookup in reject() callbacks.
     *
     * @param BaseCollection $users  Collection of User models
     * @param BaseCollection $tagIds Collection of tag IDs
     *
     * @return BaseCollection<int, BaseCollection>
     */
    protected function preloadTagStates(BaseCollection $users, BaseCollection $tagIds): BaseCollection
    {
        if ($users->isEmpty()) {
            return collect();
        }

        return TagState::whereIn('user_id', $users->pluck('id')->all())
            ->whereIn('tag_id', $tagIds->all())
            ->get()
            ->groupBy('user_id');
    }
}

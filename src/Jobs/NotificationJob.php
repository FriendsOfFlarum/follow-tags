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
use Flarum\User\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

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
     * Those among these users who ignore none of these tags, with the groups
     * that deciding what each of them can see reads.
     *
     * Done in the query that finds them, rather than by looking up each one's
     * subscription to each tag, and their groups, afterwards.
     *
     * @template TQuery of Builder<User>|BelongsToMany<User, \Flarum\Discussion\Discussion>
     *
     * @param TQuery $query
     * @param int[]  $tagIds
     *
     * @return TQuery
     */
    protected function recipientsAmong(Builder|BelongsToMany $query, array $tagIds): Builder|BelongsToMany
    {
        return $query
            ->whereNotExists(function (QueryBuilder $ignored) use ($tagIds) {
                $ignored->selectRaw('1')
                    ->from('tag_user as ignored')
                    ->whereColumn('ignored.user_id', 'users.id')
                    ->whereIn('ignored.tag_id', $tagIds)
                    ->where('ignored.subscription', 'ignore');
            })
            ->with('groups');
    }
}

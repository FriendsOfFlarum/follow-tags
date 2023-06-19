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
use Flarum\Foundation\ContainerUtil;
use Flarum\Post\Post;
use Flarum\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Queue\SerializesModels;

class FollowTagsJob implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @param int                $actorId
     * @param array              $tagIds
     * @param array              $subscriptions
     * @param BelongsToMany|null $relations
     *
     * @return Builder|BelongsToMany
     */
    protected function getNotifyUsersQuery(int $actorId, array $tagIds, array $subscriptions = ['follow', 'lurk'], BelongsToMany $relations = null)
    {
        // The `select(...)` part is not mandatory here, but makes the query safer. See #55.
        $source = $relations ? $relations->select('users.*') : User::select('users.*');

        return $source
            ->where('users.id', '!=', $actorId)
            ->join('tag_user', 'tag_user.user_id', '=', 'users.id')
            ->whereIn('tag_user.tag_id', $tagIds)
            ->whereIn('tag_user.subscription', $subscriptions);
    }

    protected function applyRejects(Collection $notify, Post $post, Collection $tags): Collection
    {
        foreach (resolve('fof-follow-tags.notify.rejects') as $closure) {
            $callback = ContainerUtil::wrapCallback($closure, resolve(Container::class));
            $notify = $notify->reject(function (User $user) use ($callback, $post, $tags) {
                return $callback($user, $post, $tags);
            });
        }

        return $notify;
    }
}

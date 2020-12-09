<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Jobs;

use Flarum\Discussion\Discussion;
use Flarum\Notification\NotificationSyncer;
use Flarum\User\User;
use FoF\FollowTags\Notifications\NewDiscussionTagBlueprint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendNotificationWhenDiscussionIsReTagged implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var User
     */
    protected $actor;

    /**
     * @var Discussion
     */
    protected $discussion;

    public function __construct(User $actor, Discussion $discussion)
    {
        $this->actor = $actor;
        $this->discussion = $discussion;
    }

    public function handle(NotificationSyncer $notifications)
    {
        if (!$this->discussion->exists()) {
            return;
        }

        /**
         * @var Collection
         * @var $tagIds    Collection
         */
        $tags = $this->discussion->tags;
        $tagIds = $tags->map->id;
        $firstPost = $this->discussion->firstPost ?? $this->discussion->posts()->orderBy('number')->first();

        if ($tags->isEmpty() || !$firstPost) {
            return;
        }

        $notify = User::where('users.id', '!=', $this->actor->id)
            ->join('tag_user', 'tag_user.user_id', '=', 'users.id')
            ->whereIn('tag_user.tag_id', $tagIds->all())
            ->whereIn('tag_user.subscription', ['follow', 'lurk'])
            ->get()
            ->reject(function ($user) use ($firstPost, $tags) {
                return $tags->map->stateFor($user)->map->subscription->contains('ignore')
                        || !$this->discussion->newQuery()->whereVisibleTo($user)->find($this->discussion->id)
                        || !$firstPost->isVisibleTo($user);
            });

        $notifications->sync(
            new NewDiscussionTagBlueprint($this->actor, $this->discussion, $firstPost),
            $notify->all()
        );
    }
}

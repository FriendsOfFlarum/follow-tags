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

use Flarum\Database\Eloquent\Collection as DatabaseCollection;
use Flarum\Discussion\Discussion;
use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Post;
use Flarum\User\User;
use FoF\FollowTags\Notifications\NewDiscussionTagBlueprint;
use Illuminate\Support\Collection;

class SendNotificationWhenDiscussionIsReTagged extends FollowTagsJob
{
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
        if (!$this->discussion->exists) {
            return;
        }

        /**
         * @var DatabaseCollection $tags
         */
        $tags = $this->discussion->tags;

        /**
         * @var Collection
         */
        $tagIds = $tags->map->id;

        /**
         * @var Post $firstPost
         */
        $firstPost = $this->discussion->firstPost ?? $this->discussion->posts()->orderBy('number')->first();

        if ($tags->isEmpty() || !$firstPost) {
            return;
        }

        $notify = $this->getNotifyUsersQuery(
            $this->actor->id,
            $tagIds->all()
        )
            ->get();
        // ->reject(function (User $user) use ($firstPost, $tags) {
            //     return $tags->map->stateFor($user)->map->subscription->contains('ignore')
            //             || !$this->discussion->newQuery()->whereVisibleTo($user)->find($this->discussion->id)
            //             || !$firstPost->isVisibleTo($user);
        // });

        $notify = $this->applyRejects($notify, $firstPost, $tags);

        $notifications->sync(
            new NewDiscussionTagBlueprint($this->actor, $this->discussion, $firstPost),
            $notify->all()
        );
    }
}

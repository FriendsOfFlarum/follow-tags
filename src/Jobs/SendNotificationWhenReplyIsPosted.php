<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2019 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Jobs;

use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Post;
use Flarum\User\User;
use FoF\FollowTags\Notifications\NewPostBlueprint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendNotificationWhenReplyIsPosted implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var Post
     */
    protected $lastPostNumber;

    public function __construct(Post $post, int $lastPostNumber)
    {
        $this->post = $post;
        $this->lastPostNumber = $lastPostNumber;
    }

    public function handle(NotificationSyncer $notifications)
    {
        /**
         * @var Collection
         * @var $tagIds    Collection
         */
        $discussion = $this->post->discussion;
        $tags = $discussion->tags;
        $tagIds = $tags->map->id;

        if ($tags->isEmpty()) {
            return;
        }

        $notify = $this->post->discussion->readers()
            ->where('users.id', '!=', $this->post->user_id)
            ->join('tag_user', 'tag_user.user_id', '=', 'users.id')
            ->whereIn('tag_user.tag_id', $tagIds->all())
            ->where('tag_user.subscription', 'lurk')
            ->where('discussion_user.last_read_post_number', '>=', $this->lastPostNumber)
            ->get()
            ->reject(function (User $user) use ($tags) {
                return $tags->map->stateFor($user)->map->subscription->contains('ignore')
                    || !$this->post->isVisibleTo($user);
            });

        $notifications->sync(
            new NewPostBlueprint($this->post),
            $notify->all()
        );
    }
}

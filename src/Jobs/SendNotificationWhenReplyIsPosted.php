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

use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Post;
use Flarum\User\User;
use FoF\FollowTags\Notifications\NewPostBlueprint;
use Illuminate\Support\Collection;

class SendNotificationWhenReplyIsPosted extends NotificationJob
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * @var int
     */
    protected $lastPostNumber;

    public function __construct(Post $post, int $lastPostNumber)
    {
        $this->post = $post;
        $this->lastPostNumber = $lastPostNumber;
    }

    public function handle(NotificationSyncer $notifications)
    {
        if (!$this->post->exists) {
            return;
        }

        $discussion = $this->post->discussion;

        /**
         * @var Collection<Tag>|null $tags
         *
         * @phpstan-ignore-next-line
         */
        $tags = $discussion->tags;
        $tagIds = $tags->map->id;

        if (!$tags || $tags->isEmpty()) {
            return;
        }

        $notify = $this->post->discussion->readers()
            // The `select(...)` part is not mandatory here, but makes the query safer. See #55.
            ->select('users.*')
            ->where('users.id', '!=', $this->post->user_id)
            ->join('tag_user', 'tag_user.user_id', '=', 'users.id')
            ->whereIn('tag_user.tag_id', $tagIds->all())
            ->where('tag_user.subscription', 'lurk')
            ->where('discussion_user.last_read_post_number', '>=', $this->lastPostNumber - 1)
            ->get()
            ->reject(function (User $user) use ($tags) {
                return $tags->map->stateFor($user)->map->subscription->contains('ignore')
                    || !$this->post->isVisibleTo($user);
            });

        $this->sync($notifications, new NewPostBlueprint($this->post), $notify);
    }
}

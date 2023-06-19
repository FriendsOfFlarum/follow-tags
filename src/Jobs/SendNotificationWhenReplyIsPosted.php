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
use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Post;
use Flarum\User\User;
use FoF\FollowTags\Notifications\NewPostBlueprint;
use Illuminate\Support\Collection;

class SendNotificationWhenReplyIsPosted extends FollowTagsJob
{
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
        if (!$this->post->exists) {
            return;
        }

        $discussion = $this->post->discussion;

        /**
         * @var DatabaseCollection
         */
        $tags = $discussion->tags;

        /**
         * @var Collection $tagIds
         */
        $tagIds = $tags->map->id;

        if (!$tags || $tags->isEmpty()) {
            return;
        }

        $notify = $this->getNotifyUsersQuery(
            $this->post->user_id,
            $tagIds->all(),
            ['lurk'],
            $this->post->discussion->readers()
        )
            ->where('discussion_user.last_read_post_number', '>=', $this->lastPostNumber - 1)
            ->get();
        // ->reject(function (User $user) use ($tags) {
            //     return $tags->map->stateFor($user)->map->subscription->contains('ignore')
            //         || !$this->post->isVisibleTo($user);
        // });

        $notify = $this->applyRejects($notify, $this->post, $tags);

        $notifications->sync(
            new NewPostBlueprint($this->post),
            $notify->all()
        );
    }
}

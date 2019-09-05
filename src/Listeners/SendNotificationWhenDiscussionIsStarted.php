<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2019 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Discussion\Event\Started;
use Flarum\Notification\NotificationSyncer;
use Flarum\Tags\Tag;
use Flarum\User\User;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;
use Illuminate\Support\Collection;

class SendNotificationWhenDiscussionIsStarted
{
    /**
     * @var NotificationSyncer
     */
    protected $notifications;

    /**
     * @param NotificationSyncer $notifications
     */
    public function __construct(NotificationSyncer $notifications)
    {
        $this->notifications = $notifications;
    }

    public function handle(Started $event)
    {
        $discussion = $event->discussion;

        /**
         * @var Collection
         * @var $tagIds    Collection
         */
        $tags = $discussion->tags;
        $tagIds = $tags->map->id;

        if ($tags->isEmpty()) {
            return;
        }

        if ($discussion->is_private) {
            foreach ($discussion->recipientUsers()->get() as $recipientUser) {
                if ($recipientUser->id === $event->actor->id) {
                    continue;
                }

                $notify[] = $recipientUser;
            }
        }

        if (!$discussion->is_private) {
            $notify = User::where('users.id', '!=', $discussion->user_id)
                ->join('tag_user', 'tag_user.user_id', '=', 'users.id')
                ->whereIn('tag_user.tag_id', $tagIds->all())
                ->whereIn('tag_user.subscription', ['follow', 'lurk'])
                ->get()
                ->reject(function ($user) use ($tags) {
                    return $tags->map->state->map->subscription->contains('ignore')
                        || $tags->whereIn('id', Tag::getIdsWhereCannot($user, 'viewDiscussions'))->isNotEmpty();
                })
                ->all();
        }

        $this->notifications->sync(
            new NewDiscussionBlueprint($discussion),
            $notify
        );
    }
}

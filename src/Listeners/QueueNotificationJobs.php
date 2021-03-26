<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Approval\Event\PostWasApproved;
use Flarum\Discussion\Event\Started;
use Flarum\Post\Event\Posted;
use Flarum\Tags\Event\DiscussionWasTagged;
use FoF\FollowTags\Jobs;
use Illuminate\Events\Dispatcher;

class QueueNotificationJobs
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Started::class, [$this, 'whenDiscussionStarted']);
        $events->listen(Posted::class, [$this, 'whenPostCreated']);
        $events->listen(PostWasApproved::class, [$this, 'whenPostApproved']);
        $events->listen(DiscussionWasTagged::class, [$this, 'whenDiscussionTagChanged']);
    }

    public function whenDiscussionStarted(Started $event)
    {
        $event->discussion->afterSave(function ($discussion) {
            app('flarum.queue.connection')->push(
                new Jobs\SendNotificationWhenDiscussionIsStarted($discussion)
            );
        });
    }

    public function whenPostCreated(Posted $event)
    {
        $event->post->afterSave(function ($post) {
            if (!$post->discussion->exists || $post->number == 1) {
                return;
            }

            app('flarum.queue.connection')->push(
                new Jobs\SendNotificationWhenReplyIsPosted($post, $post->number - 1)
            );
        });
    }

    public function whenPostApproved(PostWasApproved $event)
    {
        $event->post->afterSave(function ($post) {
            if (!$post->discussion->exists) {
                return;
            }

            app('flarum.queue.connection')->push(
                $post->number == 1
                    ? new Jobs\SendNotificationWhenDiscussionIsStarted($post->discussion)
                    : new Jobs\SendNotificationWhenReplyIsPosted($post, $post->number - 1)
            );
        });
    }

    public function whenDiscussionTagChanged(DiscussionWasTagged $event)
    {
        $event->discussion->afterSave(function ($discussion) use ($event) {
            app('flarum.queue.connection')->push(
                new Jobs\SendNotificationWhenDiscussionIsReTagged($event->actor, $discussion)
            );
        });
    }
}

<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Discussion\Event\Started;
use Flarum\Post\Event\Saving;
use Flarum\Tags\Event\DiscussionWasTagged;
use FoF\FollowTags\Jobs;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Events\Dispatcher;

class QueueNotificationJobs
{
    public function __construct(
        protected Queue $queue
    ) {
    }
    
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Started::class, [$this, 'whenDiscussionStarted']);
        $events->listen(Saving::class, [$this, 'whenPostCreated']);

        // Only listen for approval events if the extension is enabled
        if (class_exists('Flarum\Approval\Event\PostWasApproved')) {
            $events->listen('Flarum\Approval\Event\PostWasApproved', [$this, 'whenPostApproved']);
        }

        $events->listen(DiscussionWasTagged::class, [$this, 'whenDiscussionTagChanged']);
    }

    public function whenDiscussionStarted(Started $event)
    {
        $this->queue->push(
            new Jobs\SendNotificationWhenDiscussionIsStarted($event->discussion)
        );
    }

    public function whenPostCreated(Saving $event)
    {
        if ($event->post->exists) {
            return;
        }

        $queue = $this->queue;

        // Queue job after post is saved
        $event->post->afterSave(function ($post) use ($queue) {
            if (!$post->discussion->exists || $post->number == 1) {
                return;
            }

            $queue->push(
                new Jobs\SendNotificationWhenReplyIsPosted($post, $post->number - 1)
            );
        });
    }

    /**
     * @param object $event
     */
    public function whenPostApproved($event)
    {
        // Type-check the event dynamically since approval extension is optional
        if (!property_exists($event, 'post')) {
            return;
        }

        $post = $event->post;

        if (!$post->discussion->exists) {
            return;
        }

        $this->queue->push(
            $post->number == 1
                ? new Jobs\SendNotificationWhenDiscussionIsStarted($post->discussion)
                : new Jobs\SendNotificationWhenReplyIsPosted($post, $post->number - 1)
        );
    }

    public function whenDiscussionTagChanged(DiscussionWasTagged $event)
    {
        $this->queue->push(
            new Jobs\SendNotificationWhenDiscussionIsReTagged($event->actor, $event->discussion)
        );
    }
}

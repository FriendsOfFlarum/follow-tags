<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2019 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Discussion\Event as Discussion;
use Flarum\Event\ConfigureNotificationTypes;
use Flarum\Extend;
use Flarum\Notification\Event as Notification;
use Flarum\Post\Event as Post;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;
use FoF\FollowTags\Notifications\NewPostBlueprint;
use Illuminate\Contracts\View\Factory;
use Illuminate\Events\Dispatcher;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),
    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\Routes('api'))
        ->post('/tags/{id}/subscription', 'fof-follow-tags.subscription', Controllers\ChangeTagSubscription::class),

    new Extend\Compat(function (Dispatcher $events, Factory $views) {
        $events->listen(Serializing::class, Listeners\AddTagSubscriptionAttribute::class);

        $events->listen(ConfigureNotificationTypes::class, function (ConfigureNotificationTypes $event) {
            $event->add(NewDiscussionBlueprint::class, DiscussionSerializer::class, ['alert', 'email']);
            $event->add(NewPostBlueprint::class, DiscussionSerializer::class, ['alert', 'email']);
        });

        $events->subscribe(Listeners\QueueNotificationJobs::class);

        $events->listen([Discussion\Hidden::class, Discussion\Deleted::class], Listeners\DeleteNotificationWhenDiscussionIsHiddenOrDeleted::class);
        $events->listen(Discussion\Restored::class, Listeners\RestoreNotificationWhenDiscussionIsRestored::class);

        $events->listen([Post\Hidden::class, Post\Deleted::class], Listeners\DeleteNotificationWhenPostIsHiddenOrDeleted::class);
        $events->listen(Post\Restored::class, Listeners\RestoreNotificationWhenPostIsRestored::class);

        $events->listen(Discussion\Searching::class, Listeners\HideDiscussionsInIgnoredTags::class);

        $events->listen(Notification\Sending::class, Listeners\PreventMentionNotificationsFromIgnoredTags::class);

        $views->addNamespace('fof-follow-tags', __DIR__.'/resources/views');
    }),
];

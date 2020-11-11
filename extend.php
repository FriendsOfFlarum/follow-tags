<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Discussion\Event as Discussion;
use Flarum\Event\ConfigureDiscussionGambits;
use Flarum\Event\ConfigureNotificationTypes;
use Flarum\Extend;
use Flarum\Notification\Event as Notification;
use Flarum\Post\Event as Post;
use Flarum\User\User;
use FoF\Components\Extend\AddFofComponents;
use FoF\Extend\Extend\ExtensionSettings;
use FoF\FollowTags\Notifications\NewDiscussionBlueprint;
use FoF\FollowTags\Notifications\NewDiscussionTagBlueprint;
use FoF\FollowTags\Notifications\NewPostBlueprint;
use Illuminate\Events\Dispatcher;

return [
    new AddFofComponents(),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\Routes('api'))
        ->post('/tags/{id}/subscription', 'fof-follow-tags.subscription', Controllers\ChangeTagSubscription::class),

    (new Extend\View())
        ->namespace('fof-follow-tags', __DIR__.'/resources/views'),

    (new ExtensionSettings())
        ->addKey('fof-follow-tags.following_page_default'),

    (new Extend\Event())
        ->listen(Serializing::class, Listeners\AddTagSubscriptionAttribute::class)
        ->listen(ConfigureNotificationTypes::class, function (ConfigureNotificationTypes $event) {
            $event->add(NewDiscussionBlueprint::class, DiscussionSerializer::class, ['alert', 'email']);
            $event->add(NewPostBlueprint::class, DiscussionSerializer::class, ['alert', 'email']);
            $event->add(NewDiscussionTagBlueprint::class, DiscussionSerializer::class, ['alert', 'email']);
        })
        ->listen(Discussion\Deleted::class, Listeners\DeleteNotificationWhenDiscussionIsHiddenOrDeleted::class)
        ->listen(Discussion\Hidden::class, Listeners\DeleteNotificationWhenDiscussionIsHiddenOrDeleted::class)
        ->listen(Discussion\Restored::class, Listeners\RestoreNotificationWhenDiscussionIsRestored::class)
        ->listen(Post\Hidden::class, Listeners\DeleteNotificationWhenPostIsHiddenOrDeleted::class)
        ->listen(Post\Deleted::class, Listeners\DeleteNotificationWhenPostIsHiddenOrDeleted::class)
        ->listen(Post\Restored::class, Listeners\RestoreNotificationWhenPostIsRestored::class)
        ->listen(Discussion\Searching::class, Listeners\HideDiscussionsInIgnoredTags::class)
        ->listen(Notification\Sending::class, Listeners\PreventMentionNotificationsFromIgnoredTags::class)
        ->listen(ConfigureDiscussionGambits::class, function (ConfigureDiscussionGambits $event) {
            $event->gambits->add(Gambit\FollowTagsGambit::class);
        }),

    function (Dispatcher $events) {
        $events->subscribe(Listeners\QueueNotificationJobs::class);
        User::addPreference('followTagsPageDefault', null);
    },
];

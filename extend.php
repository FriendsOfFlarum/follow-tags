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

use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Discussion\Event as Discussion;
use Flarum\Event\ConfigureDiscussionGambits;
use Flarum\Extend;
use Flarum\Notification\Event as Notification;
use Flarum\Post\Event as Post;
use Flarum\Tags\Api\Serializer\TagSerializer;
use FoF\Extend\Extend\ExtensionSettings;
use Illuminate\Events\Dispatcher;

return [
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

    (new Extend\User())
        ->registerPreference('followTagsPageDefault', null),

    (new Extend\ApiSerializer(TagSerializer::class))
        ->mutate(AddTagSubscriptionAttribute::class),

    (new Extend\Notification())
        ->type(Notifications\NewDiscussionBlueprint::class, DiscussionSerializer::class, ['alert', 'email'])
        ->type(Notifications\NewPostBlueprint::class, DiscussionSerializer::class, ['alert', 'email'])
        ->type(Notifications\NewDiscussionTagBlueprint::class, DiscussionSerializer::class, ['alert', 'email']),

    function (Dispatcher $events) {
        $events->subscribe(Listeners\QueueNotificationJobs::class);
    },
];

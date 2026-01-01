<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags;

use Flarum\Api\Context;
use Flarum\Api\Schema;
use Flarum\Discussion\Event as Discussion;
use Flarum\Extend;
use Flarum\Gdpr\Extend\UserData;
use Flarum\Post\Event as Post;
use Flarum\Tags\Tag;
use Flarum\Tags\TagState;

// use FoF\Extend\Extend\ExtensionSettings;

return [
    (new Extend\Policy())
        ->modelPolicy(Tag::class, Access\TagPolicy::class),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\Model(TagState::class))
        ->cast('subscription', 'string'),

    (new Extend\View())
        ->namespace('fof-follow-tags', __DIR__.'/resources/views'),

    // @TODO: Re-enable when fof/extend is available for Flarum 2.0
    // (new ExtensionSettings())
    //     ->addKey('fof-follow-tags.following_page_default'),

    (new Extend\Event())
        ->listen(Discussion\Deleted::class, Listeners\DeleteNotificationWhenDiscussionIsHiddenOrDeleted::class)
        ->listen(Discussion\Hidden::class, Listeners\DeleteNotificationWhenDiscussionIsHiddenOrDeleted::class)
        ->listen(Discussion\Restored::class, Listeners\RestoreNotificationWhenDiscussionIsRestored::class)
        ->listen(Post\Hidden::class, Listeners\DeleteNotificationWhenPostIsHiddenOrDeleted::class)
        ->listen(Post\Deleted::class, Listeners\DeleteNotificationWhenPostIsHiddenOrDeleted::class)
        ->listen(Post\Restored::class, Listeners\RestoreNotificationWhenPostIsRestored::class)
        ->subscribe(Listeners\QueueNotificationJobs::class),

    (new Extend\User())
        ->registerPreference('followTagsPageDefault'),

    (new Extend\ApiResource(\Flarum\Tags\Api\Resource\TagResource::class))
        ->fields(function () {
            return [
                // Override core tag fields to require admin permission for editing
                // This ensures our FORCE_ALLOW policy for subscriptions doesn't accidentally
                // allow regular users to edit tag metadata
                Schema\Str::make('name')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => $context->getActor()->isAdmin()),
                Schema\Str::make('slug')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => $context->getActor()->isAdmin()),
                Schema\Str::make('description')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => $context->getActor()->isAdmin()),
                Schema\Str::make('color')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => $context->getActor()->isAdmin()),
                Schema\Str::make('icon')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => $context->getActor()->isAdmin()),
                Schema\Boolean::make('isHidden')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => $context->getActor()->isAdmin()),
                Schema\Boolean::make('isPrimary')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => $context->getActor()->isAdmin()),

                // Our subscription field - writable by all registered users
                Schema\Str::make('subscription')
                    ->writable(fn (\Flarum\Tags\Tag $_, Context $context) => !$context->getActor()->isGuest())
                    ->nullable()
                    ->get(function (\Flarum\Tags\Tag $tag, Context $context) {
                        $actor = $context->getActor();

                        if (!$tag->relationLoaded('state') || is_null($tag->state) || $tag->state->user_id !== $actor->id) {
                            $tag->setRelation('state', $tag->stateFor($actor));
                        }

                        return $tag->state->subscription ?? null;
                    })
                    ->set(function (\Flarum\Tags\Tag $tag, ?string $subscription, Context $context) {
                        $actor = $context->getActor();
                        $actor->assertRegistered();

                        $state = $tag->stateFor($actor);

                        if (!in_array($subscription, ['follow', 'lurk', 'ignore', 'hide'])) {
                            $subscription = null;
                        }

                        $state->subscription = $subscription;
                        $state->save();
                    }),
            ];
        }),

    (new Extend\Notification())
        ->type(Notifications\NewDiscussionBlueprint::class, ['alert', 'email'])
        ->type(Notifications\NewPostBlueprint::class, ['alert', 'email'])
        ->type(Notifications\NewDiscussionTagBlueprint::class, ['alert', 'email'])
        ->beforeSending(Listeners\PreventMentionNotificationsFromIgnoredTags::class),

    (new Extend\Conditional())
        ->whenExtensionEnabled('flarum-gdpr', fn () => [
            (new UserData())
                ->addType(Data\TagSubscription::class),
        ]),

    (new Extend\SearchDriver(\Flarum\Search\Database\DatabaseSearchDriver::class))
        ->addFilter(\Flarum\Discussion\Search\DiscussionSearcher::class, Search\FollowTagsFilter::class)
        ->addMutator(\Flarum\Discussion\Search\DiscussionSearcher::class, Search\HideTagsFilter::class),
];

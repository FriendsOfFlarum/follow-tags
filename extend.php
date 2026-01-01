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
use Flarum\Api\Endpoint\Update;
use Flarum\Api\Schema;
use Flarum\Discussion\Event as Discussion;
use Flarum\Extend;
use Flarum\Gdpr\Extend\UserData;
use Flarum\Post\Event as Post;
use Flarum\Tags\TagState;

return [
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

    (new Extend\Settings())
        ->default('fof-follow-tags.following_page_default', 'none'),

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
        ->endpoint(Update::class, function (\Flarum\Api\Endpoint\Endpoint $endpoint) {
            // Custom authorization check that allows subscription-only updates
            // or full edits for users with edit permission
            return $endpoint->can(function (\Flarum\Tags\Tag $tag, Context $context) {
                $actor = $context->getActor();

                // Check if actor can edit the tag (admin or has tag edit permission)
                if ($actor->can('edit', $tag)) {
                    return 'edit';
                }

                // If not, check if they're only updating subscription
                $attributes = \Illuminate\Support\Arr::get($context->body(), 'data.attributes', []);
                $relationships = \Illuminate\Support\Arr::get($context->body(), 'data.relationships', []);

                // If no attributes and no relationships are being updated, allow (will be caught by validation)
                if (empty($attributes) && empty($relationships)) {
                    return null;
                }

                // Check if only subscription is being updated
                $isOnlySubscription = count($attributes) === 1
                    && array_key_exists('subscription', $attributes)
                    && empty($relationships);

                if ($isOnlySubscription && !$actor->isGuest()) {
                    // Allow subscription updates - bypass the 'edit' check
                    return null;
                }

                // Otherwise require edit permission
                return 'edit';
            });
        })
        ->fields(function () {
            return [
                // Our subscription field - writable by all registered users who can view the tag
                // The endpoint-level authorization above ensures users can only access this
                // endpoint if they're either editing the tag OR only updating subscription
                Schema\Str::make('subscription')
                    ->writable(fn (\Flarum\Tags\Tag $tag, Context $context) => $context->updating() && !$context->getActor()->isGuest())
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

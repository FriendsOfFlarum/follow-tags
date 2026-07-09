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

use Flarum\Api\Controller\ShowForumController;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Discussion\Event as Discussion;
use Flarum\Discussion\Filter\DiscussionFilterer;
use Flarum\Extend;
use Flarum\Gdpr\Extend\UserData;
use Flarum\Post\Event as Post;
use Flarum\Tags\Api\Serializer\TagSerializer;
use Flarum\Tags\TagState;
use Flarum\User\Event\Saving as UserSaving;
use Flarum\User\User;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\Model(TagState::class))
        ->cast('subscription', 'string'),

    (new Extend\Model(User::class))
        ->cast('fof_follow_tags_prompt_configured_at', 'datetime'),

    (new Extend\Routes('api'))
        ->post('/tags/{id}/subscription', 'fof-follow-tags.subscription', Controllers\ChangeTagSubscription::class),

    (new Extend\View())
        ->namespace('fof-follow-tags', __DIR__.'/resources/views'),

    (new Extend\Settings())
        ->default('fof-follow-tags.following_page_default', 'none')
        ->default('fof-follow-tags.prompt_new_users', '0')
        ->default('fof-follow-tags.prompt_button_on_following_page', '0')
        ->default('fof-follow-tags.prompt_tag_strategy', 'primary')
        ->default('fof-follow-tags.prompt_tag_ids', '[]')
        ->default('fof-follow-tags.all_discussions_on_following_page_for_guests', '0')
        ->serializeToForum('fofFollowTagsFollowingPageDefault', 'fof-follow-tags.following_page_default'),

    (new Extend\ApiSerializer(ForumSerializer::class))
        ->attributes(AddForumPromptAttributes::class)
        ->hasMany('fofFollowTagsPromptList', TagSerializer::class),

    (new Extend\ApiController(ShowForumController::class))
        // Loading the parent prevents the Flarum Tags IndexPage side navigation
        // from mistaking second-level tags for first-level tags
        ->addInclude(['fofFollowTagsPromptList.parent'])
        ->prepareDataForSerialization(LoadPromptTags::class),

    (new Extend\Event())
        ->listen(UserSaving::class, Listeners\MarkPromptAsConfigured::class)
        ->listen(Discussion\Deleted::class, Listeners\DeleteNotificationWhenDiscussionIsHiddenOrDeleted::class)
        ->listen(Discussion\Hidden::class, Listeners\DeleteNotificationWhenDiscussionIsHiddenOrDeleted::class)
        ->listen(Discussion\Restored::class, Listeners\RestoreNotificationWhenDiscussionIsRestored::class)
        ->listen(Post\Hidden::class, Listeners\DeleteNotificationWhenPostIsHiddenOrDeleted::class)
        ->listen(Post\Deleted::class, Listeners\DeleteNotificationWhenPostIsHiddenOrDeleted::class)
        ->listen(Post\Restored::class, Listeners\RestoreNotificationWhenPostIsRestored::class)
        ->subscribe(Listeners\QueueNotificationJobs::class),

    (new Extend\Filter(DiscussionFilterer::class))
        ->addFilter(Search\FollowTagsFilter::class)
        ->addFilterMutator(Search\HideTagsFilter::class),

    (new Extend\User())
        ->registerPreference('followTagsPageDefault'),

    (new Extend\ApiSerializer(TagSerializer::class))
        ->attributes(AddTagSubscriptionAttribute::class),

    (new Extend\Notification())
        ->type(Notifications\NewDiscussionBlueprint::class, DiscussionSerializer::class, ['alert', 'email'])
        ->type(Notifications\NewPostBlueprint::class, DiscussionSerializer::class, ['alert', 'email'])
        ->type(Notifications\NewDiscussionTagBlueprint::class, DiscussionSerializer::class, ['alert', 'email'])
        ->beforeSending(Listeners\PreventMentionNotificationsFromIgnoredTags::class),

    (new Extend\Conditional())
        ->whenExtensionEnabled('flarum-gdpr', fn () => [
            (new UserData())
                ->addType(Data\TagSubscription::class),
        ]),
];

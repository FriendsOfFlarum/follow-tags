import { extend } from 'flarum/extend';
import NotificationGrid from 'flarum/components/NotificationGrid';

import addSubscriptionControls from './addSubscriptionControls';
import addFollowedTagsDiscussions from './addFollowedTagsDiscussions';

import NewDiscussionNotification from './components/NewDiscussionNotification';
import NewPostNotification from './components/NewPostNotification';
import NewDiscussionTagNotification from './components/NewDiscussionTagNotification';
import addDiscussionBadge from './addDiscussionBadge';
import addPreferences from './addPreferences';

export * from './components';
export * from './utils';

app.initializers.add(
    'fof/follow-tags',
    () => {
        if (!app.initializers.has('flarum-tags')) {
            console.error('[fof/follow-tags] flarum/tags is not enabled');
            return;
        }

        addSubscriptionControls();

        if (app.initializers.has('subscriptions')) {
            addDiscussionBadge();
            addFollowedTagsDiscussions();
            addPreferences();
        }

        app.notificationComponents.newPostInTag = NewPostNotification;
        app.notificationComponents.newDiscussionInTag = NewDiscussionNotification;
        app.notificationComponents.newDiscussionTag = NewDiscussionTagNotification;

        extend(NotificationGrid.prototype, 'notificationTypes', function (items) {
            items.add('newDiscussionInTag', {
                name: 'newDiscussionInTag',
                icon: 'fas fa-user-tag',
                label: app.translator.trans('fof-follow-tags.forum.settings.notify_new_discussion_label'),
            });

            items.add('newPostInTag', {
                name: 'newPostInTag',
                icon: 'fas fa-user-tag',
                label: app.translator.trans('fof-follow-tags.forum.settings.notify_new_post_label'),
            });

            items.add('newDiscussionTag', {
                name: 'newDiscussionTag',
                icon: 'fas fa-user-tag',
                label: app.translator.trans('fof-follow-tags.forum.settings.notify_new_discussion_tag_label'),
            });
        });
    },
    -1
);

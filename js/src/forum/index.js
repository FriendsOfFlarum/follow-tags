import { extend } from 'flarum/extend';
import NotificationGrid from 'flarum/components/NotificationGrid';

import addSubscriptionControls from './addSubscriptionControls';
import NewDiscussionNotification from './components/NewDiscussionNotification';
import NewPostNotification from './components/NewPostNotification';
import NewDiscussionTagNotification from './components/NewDiscussionTagNotification';

app.initializers.add(
    'fof/follow-tags',
    () => {
        if (!app.initializers.has('flarum-tags')) {
            console.error('!! flarum/tags is not enabled');
            return;
        }

        addSubscriptionControls();

        app.notificationComponents.newPostInTag = NewPostNotification;
        app.notificationComponents.newDiscussionInTag = NewDiscussionNotification;
        app.notificationComponents.newDiscussionTag = NewDiscussionTagNotification;

        extend(NotificationGrid.prototype, 'notificationTypes', function(items) {
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

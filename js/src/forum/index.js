import { extend } from 'flarum/extend';
import NotificationGrid from 'flarum/components/NotificationGrid';

import addSubscriptionControls from './addSubscriptionControls';
import NewDiscussionNotification from './components/NewDiscussionNotification';
import NewPostNotification from './components/NewPostNotification';

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
        });
    },
    -1
);

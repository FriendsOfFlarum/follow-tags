import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import NotificationGrid from 'flarum/forum/components/NotificationGrid';

export default function extendNotificationGrid() {
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
}

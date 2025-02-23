import app from 'flarum/forum/app';
import addSubscriptionControls from './addSubscriptionControls';
import addFollowedTagsDiscussions from './addFollowedTagsDiscussions';
import NewDiscussionNotification from './components/NewDiscussionNotification';
import NewPostNotification from './components/NewPostNotification';
import NewDiscussionTagNotification from './components/NewDiscussionTagNotification';
import addDiscussionBadge from './addDiscussionBadge';
import addPreferences from './addPreferences';
import extendNotificationGrid from './extendNotificationGrid';
import extendIndexPage from './extenders/extendIndexPage';

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
    extendIndexPage();

    if ('flarum-subscriptions' in flarum.extensions) {
      addDiscussionBadge();
      addFollowedTagsDiscussions();
      addPreferences();
    }

    app.notificationComponents.newPostInTag = NewPostNotification;
    app.notificationComponents.newDiscussionInTag = NewDiscussionNotification;
    app.notificationComponents.newDiscussionTag = NewDiscussionTagNotification;

    extendNotificationGrid();
  },
  -1
);

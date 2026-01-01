import app from 'flarum/forum/app';
import addFollowedTagsDiscussions from './addFollowedTagsDiscussions';
import addDiscussionBadge from './addDiscussionBadge';
import addPreferences from './addPreferences';
import extendNotificationGrid from './extendNotificationGrid';
import extendIndexPage from './extenders/extendIndexPage';

export { default as extend } from './extend';

app.initializers.add(
  'fof/follow-tags',
  () => {
    extendIndexPage();

    if ('flarum-subscriptions' in flarum.extensions) {
      addDiscussionBadge();
      addFollowedTagsDiscussions();
      addPreferences();
    }

    extendNotificationGrid();
  },
  -1
);

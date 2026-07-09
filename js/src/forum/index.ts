import app from 'flarum/forum/app';
import addFollowedTagsDiscussions from './addFollowedTagsDiscussions';
import addDiscussionBadge from './addDiscussionBadge';
import addNewUserTagsPrompt from './addNewUserTagsPrompt';
import addPreferences from './addPreferences';
import extendNotificationGrid from './extendNotificationGrid';
import extendFollowingPage from './extenders/extendFollowingPage';
import extendIndexPage from './extenders/extendIndexPage';

export { default as extend } from './extend';

app.initializers.add(
  'fof/follow-tags',
  () => {
    extendIndexPage();
    addNewUserTagsPrompt();

    if ('flarum-subscriptions' in flarum.extensions) {
      addDiscussionBadge();
      addFollowedTagsDiscussions();
      addPreferences();
      extendFollowingPage();
    }

    extendNotificationGrid();
  },
  -1
);

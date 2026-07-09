import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import Page from 'flarum/common/components/Page';
import IndexPage from 'flarum/forum/components/IndexPage';

export default function addNewUserTagsPrompt() {
  let initialized = false;

  extend(Page.prototype, 'oninit', function () {
    if (initialized) {
      return;
    }

    initialized = true;

    // Only show the modal if the first page loaded was the index page,
    // and the user still needs to choose tags to follow
    if ((app.current.matches(IndexPage) || m.route.get() === '/') && app.forum.attribute('fofFollowTagsPromptShouldPrompt')) {
      // This code is affected by the blue backdrop of death https://github.com/flarum/core/issues/1813
      // This can't be reliably reproduced, but happens mostly on Firefox
      // setTimeout doesn't solve the issue but brings a slight improvement, so we'll go with that for now
      setTimeout(() => app.modal.show(() => import('./components/ChooseTagsToFollowModal'), { hasNotChosenYet: true }), 0);
    }
  });
}

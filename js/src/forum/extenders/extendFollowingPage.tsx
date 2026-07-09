import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import IndexPage from 'flarum/forum/components/IndexPage';
import DiscussionListState from 'flarum/forum/states/DiscussionListState';
import Button from 'flarum/common/components/Button';
import ItemList from 'flarum/common/utils/ItemList';
import type Mithril from 'mithril';
import isFollowingPage from '../utils/isFollowingPage';

export default function extendFollowingPage() {
  extend(DiscussionListState.prototype, 'requestParams', function (params: any) {
    if (!isFollowingPage() || app.session.user || !app.forum.attribute('fofFollowTagsAllDiscussionsForGuests')) return;

    // If this is the following page and we are a guest, show all discussions as
    // if it was the homepage. That way the following page can be used as the
    // homepage without a negative impact on guests.
    delete params.filter['following-tag'];
    delete params.filter.subscription;
  });

  extend(IndexPage.prototype, 'viewItems', function (items: ItemList<Mithril.Children>) {
    if (!isFollowingPage() || !app.session.user || !app.forum.attribute('fofFollowTagsPromptButton')) return;

    items.add(
      'chooseTagsToFollow',
      <Button className="Button Button--primary" onclick={() => app.modal.show(() => import('../components/ChooseTagsToFollowModal'))}>
        {app.translator.trans('fof-follow-tags.forum.prompt.choose_button')}
      </Button>,
      -10
    );
  });
}

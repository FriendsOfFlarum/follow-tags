import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import Discussion from 'flarum/common/models/Discussion';
import Badge from 'flarum/common/components/Badge';
import ItemList from 'flarum/common/utils/ItemList';
import type Mithril from 'mithril';

import isFollowingPage from './utils/isFollowingPage';

export default function addSubscriptionBadge(): void {
  extend(Discussion.prototype, 'badges', function (badges: ItemList<Mithril.Children>) {
    const tags = this.tags();
    if (!isFollowingPage() || !tags || (tags as any) === false) {
      return;
    }

    const subscriptions = tags.map((tag: any) => tag.subscription()).filter((state: any) => ['lurk', 'follow'].includes(state));

    const type = subscriptions.includes('lurk') ? 'lurking' : 'following';

    if (subscriptions.length) {
      badges.add(
        'followTags',
        Badge.component({
          label: app.translator.trans(`fof-follow-tags.forum.badge.${type}_tag_tooltip`),
          icon: 'fas fa-user-tag',
          type: `${type}-tag`,
        })
      );
    }
  });
}

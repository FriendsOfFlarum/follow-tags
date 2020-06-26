import { extend } from 'flarum/extend';
import Discussion from 'flarum/models/Discussion';
import Badge from 'flarum/components/Badge';

import isFollowingPage from './utils/isFollowingPage';

export default function addSubscriptionBadge() {
    extend(Discussion.prototype, 'badges', function (badges) {
        if (!isFollowingPage()) {
            return;
        }

        const subscriptions = this.tags()
            .map((tag) => tag.subscription())
            .filter((state) => ['lurk', 'follow'].includes(state));

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

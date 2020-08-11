import { extend } from 'flarum/extend';

import DiscussionList from 'flarum/components/DiscussionList';
import IndexPage from 'flarum/components/IndexPage';

import isFollowingPage from './utils/isFollowingPage';

import { getDefaultFollowingFiltering } from './utils/getDefaultFollowingFiltering';
import FollowingPageFilterDropdown from './components/FollowingPageFilterDropdown';

export default () => {
    extend(DiscussionList.prototype, 'requestParams', function (params) {
        if (!isFollowingPage() || !app.session.user) return;

        if (!this.followTags) {
            this.followTags = getDefaultFollowingFiltering();
        }

        let q = params.filter.q || '';
        const filter = m.route.param().filter;
        const followTags = this.followTags;

        if (filter === 'following' && ['tags', 'all'].includes(followTags)) {
            if (followTags === 'tags' || followTags === 'all') {
                q += ' is:following-tag';
            }

            if (followTags === 'tags') {
                q = q.replace(' is:following', '');
            }

            params.filter.q = q;
        }
    });

    extend(IndexPage.prototype, 'viewItems', function (items) {
        if (!isFollowingPage() || !app.session.user) {
            return;
        }

        items.add('follow-tags', <FollowingPageFilterDropdown />);
    });
};

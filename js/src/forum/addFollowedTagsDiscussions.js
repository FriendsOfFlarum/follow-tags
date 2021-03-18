import { extend } from 'flarum/common/extend';

import IndexPage from 'flarum/forum/components/IndexPage';
import DiscussionListState from 'flarum/forum/states/DiscussionListState';

import isFollowingPage from './utils/isFollowingPage';

import { getDefaultFollowingFiltering } from './utils/getDefaultFollowingFiltering';
import FollowingPageFilterDropdown from './components/FollowingPageFilterDropdown';

export default () => {
    extend(DiscussionListState.prototype, 'requestParams', function (params) {
        if (!isFollowingPage() || !app.session.user) return;

        if (!this.followTags) {
            this.followTags = getDefaultFollowingFiltering();
        }

        const followTags = this.followTags;

        if (app.current.get('routeName') === 'following' && followTags === 'tags') {
            params.filter['following-tag'] = true;

            delete params.filter.subscription;
        }
    });

    extend(IndexPage.prototype, 'viewItems', function (items) {
        if (!isFollowingPage() || !app.session.user) {
            return;
        }

        items.add('follow-tags', <FollowingPageFilterDropdown />);
    });
};

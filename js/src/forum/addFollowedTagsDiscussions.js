import { extend } from 'flarum/extend';

import DiscussionList from 'flarum/components/DiscussionList';
import IndexPage from 'flarum/components/IndexPage';

import Button from 'flarum/components/Button';
import Dropdown from 'flarum/components/Dropdown';

import isFollowingPage from './utils/isFollowingPage';

export default () => {
    const options = {
        none: app.translator.trans('fof-follow-tags.index.following.following_none_label'),
        tags: app.translator.trans('fof-follow-tags.index.following.following_tags_label'),
        all: app.translator.trans('fof-follow-tags.index.following.following_all_label'),
    };

    extend(DiscussionList.prototype, 'requestParams', function(params) {
        if (!isFollowingPage() || !app.session.user) return;

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

    extend(IndexPage.prototype, 'viewItems', function(items) {
        if (!isFollowingPage()) {
            return;
        }

        const selected = app.cache.discussionList.followTags || 'none';

        items.add(
            'follow-tags',
            Dropdown.component({
                buttonClassName: 'Button',
                label: options[selected] || options.none,
                children: Object.keys(options).map(key => {
                    const active = key === selected;

                    return Button.component({
                        active,
                        children: options[key],
                        icon: active ? 'fas fa-check' : true,
                        onclick: () => {
                            if (key === 'none') delete app.cache.discussionList.followTags;
                            else app.cache.discussionList.followTags = key;

                            app.cache.discussionList.refresh();
                        },
                    });
                }),
            })
        );
    });
};

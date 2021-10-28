import { extend } from 'flarum/common/extend';
import Model from 'flarum/common/Model';
import IndexPage from 'flarum/forum/components/IndexPage';

import SubscriptionMenu from './components/SubscriptionMenu';

export default () => {
    app.store.models.tags.prototype.subscription = Model.attribute('subscription');

    extend(IndexPage.prototype, 'sidebarItems', function (items) {
        if (!this.currentTag() || !app.session.user) return;

        const tag = this.currentTag();

        if (items.has('newDiscussion')) items.replace('newDiscussion', null, 10);

        items.add('subscription', SubscriptionMenu.component({ model: tag, itemClassName: 'App-primaryControl' }), 5);
    });
};

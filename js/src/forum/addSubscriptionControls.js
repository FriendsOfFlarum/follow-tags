import { extend } from 'flarum/extend';
import Model from 'flarum/Model';
import IndexPage from 'flarum/components/IndexPage';

import SubscriptionMenu from './components/SubscriptionMenu';

export default () => {
    app.store.models.tags.prototype.subscription = Model.attribute('subscription');

    extend(IndexPage.prototype, 'sidebarItems', function (items) {
        if (!this.currentTag() || !app.session.user) return;

        const tag = this.currentTag();

        items.replace('newDiscussion', items.get('newDiscussion'), 10);

        items.add('subscription', SubscriptionMenu.component({ model: tag, itemClassName: 'App-primaryControl' }), 5);
    });
};

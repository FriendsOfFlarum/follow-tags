import app from 'flarum/admin/app';
import { extend } from 'flarum/common/extend';
import BasicsPage from 'flarum/admin/components/BasicsPage';

export { default as extend } from './extend';

app.initializers.add('fof/follow-tags', () => {
  // Because neither flarum/subscriptions nor fof/follow-tags does it, we offer
  // the Following page as a homepage option here
  extend(BasicsPage, 'homePageItems', (items) => {
    items.add('following', {
      path: '/following',
      label: app.translator.trans('fof-follow-tags.admin.basics.following_label'),
    });
  });
});

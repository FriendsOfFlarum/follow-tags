import IndexSidebar from 'flarum/forum/components/IndexSidebar';
import app from 'flarum/forum/app';
import { extend, override } from 'flarum/common/extend';
import IndexPage from 'flarum/forum/components/IndexPage';
import FollowingHero from '../components/FollowingHero';
import SubscriptionStateButton from '../components/SubscriptionStateButton';

export default function extendIndexPage() {
  extend(IndexSidebar.prototype, 'items', function (items) {
    if (!app.currentTag() || !app.session.user) return;

    const tag = app.currentTag();
    if (!tag) return;

    if (items.has('newDiscussion')) items.setPriority('newDiscussion', 10);

    items.add(
      'subscriptionButton',
      <SubscriptionStateButton
        className="Button App-primaryControl"
        subscription={tag.subscription()}
        onclick={() => app.modal.show(() => import('../components/SubscriptionModal'), { model: tag })}
      />,
      5
    );
  });

  override(IndexPage.prototype, 'hero', function (original: any) {
    if (app.current.get('routeName') === 'following') {
      return <FollowingHero />;
    }

    return original();
  });
}

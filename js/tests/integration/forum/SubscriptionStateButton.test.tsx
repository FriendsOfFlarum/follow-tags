import mq from 'mithril-query';
import SubscriptionStateButton from '../../../src/forum/components/SubscriptionStateButton';
import { bootForum } from '../../helpers/forum';

beforeAll(() => bootForum());

describe("the button showing a tag's subscription", () => {
  it.each([
    ['follow', 'Following', 'fa-star'],
    ['lurk', 'Lurking', 'fa-eye'],
    ['ignore', 'Ignoring', 'fa-bell-slash'],
    ['hide', 'Hiding', 'fa-eye-slash'],
  ])('shows %s as "%s"', (subscription, label, icon) => {
    const out = mq(SubscriptionStateButton, { subscription });

    expect(out).toContainRaw(label);
    expect(out).toHaveElement(`.SubscriptionButton--${subscription}`);
    expect(out).toHaveElement(`.fa-${icon.slice(3)}`);
  });

  it('offers to follow a tag with no subscription', () => {
    const out = mq(SubscriptionStateButton, { subscription: null });

    expect(out).toContainRaw('Follow');
    expect(out).toHaveElement('.SubscriptionButton--false');
  });
});

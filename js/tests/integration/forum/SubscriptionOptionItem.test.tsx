import mq from 'mithril-query';
import { jest } from '@jest/globals';
import SubscriptionOptionItem from '../../../src/forum/components/SubscriptionOptionItem';
import subscriptionOptions from '../../../src/forum/utils/subscriptionOptions';
import { bootForum } from '../../helpers/forum';

beforeAll(() => bootForum());

const lurk = subscriptionOptions.find((option) => option.subscription === 'lurk')!;

describe('a subscription option', () => {
  it('shows its translated label, description and icon', () => {
    const out = mq(SubscriptionOptionItem, { ...lurk, active: false, onclick: () => {} });

    expect(out).toContainRaw('Lurking');
    expect(out).toContainRaw('Be notified of all new discussions and replies.');
    expect(out).toHaveElement('.SubscriptionOption-icon.fa-eye');
    expect(out).not.toHaveElement('.SubscriptionOption.selected');
    expect(out).not.toHaveElement('.SubscriptionOption-selectedIcon');
  });

  it('is marked when chosen', () => {
    const out = mq(SubscriptionOptionItem, { ...lurk, active: true, onclick: () => {} });

    expect(out).toHaveElement('.SubscriptionOption.selected');
    expect(out).toHaveElement('.SubscriptionOption-selectedIcon');
  });

  it('is chosen by clicking it', () => {
    const onclick = jest.fn();

    mq(SubscriptionOptionItem, { ...lurk, active: false, onclick }).click('.SubscriptionOption');

    expect(onclick).toHaveBeenCalled();
  });

  it("can't be chosen when disabled", () => {
    const onclick = jest.fn();
    const out = mq(SubscriptionOptionItem, { ...lurk, active: false, disabled: true, onclick });

    expect(out).toHaveElement('.SubscriptionOption.disabled');
    expect(out).toHaveElementAttr('.SubscriptionOption', 'aria-disabled', 'true');

    out.click('.SubscriptionOption');

    expect(onclick).not.toHaveBeenCalled();
  });
});

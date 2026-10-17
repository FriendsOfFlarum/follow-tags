import mq from 'mithril-query';
import { jest } from '@jest/globals';
import { extend } from 'flarum/common/extend';
import ModalManagerState from 'flarum/common/states/ModalManagerState';
import type Tag from 'ext:flarum/tags/common/models/Tag';
import SubscriptionModal from '../../../src/forum/components/SubscriptionModal';
import { bootForum, makeTag } from '../../helpers/forum';

beforeAll(() => bootForum());

/** The title of the option marked as chosen. */
const selected = (out: ReturnType<typeof mq>) => out.find('.SubscriptionOption.selected .SubscriptionOption-title')[0]?.textContent;

const modal = (model: Tag) => mq(SubscriptionModal, { model, state: new ModalManagerState(), animateShow: () => {}, animateHide: () => {} });

describe('choosing a subscription for a tag', () => {
  it('offers every subscription option, marking the current one', () => {
    const out = modal(makeTag('1', { subscription: 'lurk' }));

    expect(out).toContainRaw('Subscription for Tag 1');
    expect(out.find('.SubscriptionOption')).toHaveLength(5);
    expect(out.find('.SubscriptionOption.selected')).toHaveLength(1);
    expect(selected(out)).toBe('Lurking');
  });

  it('marks not following when the tag has no subscription', () => {
    const out = modal(makeTag('2'));

    expect(selected(out)).toBe('Not Following');
  });

  it('saves the chosen subscription', () => {
    const tag = makeTag('4');
    const save = jest.spyOn(tag, 'save').mockResolvedValue(tag as never);
    const out = modal(tag);

    out.click('.SubscriptionOption:nth-of-type(3)');

    expect(selected(out)).toBe('Lurking');

    out.click('.Button--primary');

    expect(save).toHaveBeenCalledWith({ subscription: 'lurk' });
  });

  it('lets other extensions save their own attributes with it', () => {
    const tag = makeTag('5', { subscription: 'follow' });
    const save = jest.spyOn(tag, 'save').mockResolvedValue(tag as never);

    extend(SubscriptionModal.prototype, 'requestData', function (data: Record<string, unknown>) {
      data.subscriptionLanguage = 'de';
    });

    modal(tag).click('.Button--primary');

    expect(save).toHaveBeenCalledWith({ subscription: 'follow', subscriptionLanguage: 'de' });
  });
});

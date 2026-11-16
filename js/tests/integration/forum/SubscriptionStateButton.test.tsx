import { jest } from '@jest/globals';
import mq from 'mithril-query';
import app from 'flarum/forum/app';
import type User from 'flarum/common/models/User';
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

describe('the tooltip explaining what following does', () => {
  function signIn(preferences: Record<string, boolean>) {
    app.store.pushPayload({ data: { type: 'users', id: '7', attributes: { username: 'reader', preferences } } });
    app.session.user = app.store.getById<User>('users', '7')!;
  }

  afterEach(() => {
    app.session.user = null;
  });

  /**
   * The options core's Tooltip created the button's tooltip with, if it has one.
   */
  function tooltipOf(subscription: string | null) {
    const tooltip = jest.spyOn($.fn, 'tooltip');

    try {
      mq(SubscriptionStateButton, { subscription });

      return tooltip.mock.calls.map(([options]) => options).find((options) => typeof options === 'object') as
        | { trigger: string; title: string }
        | undefined;
    } finally {
      tooltip.mockRestore();
    }
  }

  it('shows on hover and focus, by email, to someone not following who is emailed of new posts', () => {
    signIn({ notify_newPostInTag_email: true, notify_newPostInTag_alert: true });

    const tooltip = tooltipOf(null);

    expect(tooltip!.trigger).toBe('hover focus');
    expect(tooltip!.title).toBe('Get an email when there are new discussions or posts');
  });

  it('mentions forum notifications to someone only notified by alert', () => {
    signIn({ notify_newPostInTag_email: false, notify_newPostInTag_alert: true });

    const tooltip = tooltipOf(null);

    expect(tooltip!.trigger).toBe('hover focus');
    expect(tooltip!.title).toBe('Get a forum notification when there are new discussions or posts');
  });

  it('is not offered to someone who would not be notified of new posts', () => {
    signIn({ notify_newPostInTag_email: false, notify_newPostInTag_alert: false });

    expect(tooltipOf(null)).toBeUndefined();
  });

  it.each(['follow', 'lurk', 'ignore', 'hide'])('is not offered to someone whose subscription is %s', (subscription) => {
    signIn({ notify_newPostInTag_email: true, notify_newPostInTag_alert: true });

    expect(tooltipOf(subscription)).toBeUndefined();
  });

  it('comes and goes as the subscription changes', () => {
    signIn({ notify_newPostInTag_email: true, notify_newPostInTag_alert: true });

    const tooltip = jest.spyOn($.fn, 'tooltip');
    const created = () => tooltip.mock.calls.filter(([options]) => typeof options === 'object') as unknown as [{ trigger: string }][];
    const destroyed = () => tooltip.mock.calls.filter(([action]) => action === 'destroy').length;
    const state: { subscription: string | null } = { subscription: null };

    try {
      const out = mq({ view: () => <SubscriptionStateButton subscription={state.subscription} /> });
      expect(created().map(([options]) => options.trigger)).toEqual(['hover focus']);
      const destroyedOnMount = destroyed();

      state.subscription = 'follow';
      out.redraw();
      expect(destroyed()).toBe(destroyedOnMount + 1);
      expect(created()).toHaveLength(1);

      state.subscription = null;
      out.redraw();
      expect(created().map(([options]) => options.trigger)).toEqual(['hover focus', 'hover focus']);
    } finally {
      tooltip.mockRestore();
    }
  });

  it('is not offered to guests', () => {
    expect(tooltipOf(null)).toBeUndefined();
  });
});

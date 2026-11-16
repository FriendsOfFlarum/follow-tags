import app from 'flarum/forum/app';
import Button, { IButtonAttrs } from 'flarum/common/components/Button';
import subscriptionOptions from '../utils/subscriptionOptions';
import Tooltip from 'flarum/common/components/Tooltip';
import extractText from 'flarum/common/utils/extractText';
import Stream from 'flarum/common/utils/Stream';
import type Mithril from 'mithril';

export interface ISubscriptionStateButtonAttrs extends IButtonAttrs {
  subscription?: string | null | false;
  className?: string;
  tooltipPosition?: 'top' | 'bottom' | 'left' | 'right';
}

export default class SubscriptionStateButton extends Button<ISubscriptionStateButtonAttrs> {
  loading!: Stream<boolean>;

  oninit(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>): void {
    super.oninit(vnode);

    this.loading = Stream(false);
  }

  view(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>) {
    const subscription = this.attrs.subscription || false;
    const option = subscriptionOptions.find((opt) => opt.subscription === subscription);

    const buttonIcon = option ? option.icon : 'fas fa-star';
    const buttonLabel = option ? app.translator.trans(option.labelKey) : app.translator.trans('fof-follow-tags.forum.sub_controls.follow_button');

    this.attrs.className = (this.attrs.className || '') + ' SubscriptionButton ' + 'SubscriptionButton--' + subscription;
    this.attrs.icon = buttonIcon;

    const button = super.view(Object.assign({}, vnode, { children: buttonLabel }));

    const preferences = app.session.user?.preferences();
    const notifyEmail = preferences?.['notify_newPostInTag_email'];
    const notifyAlert = preferences?.['notify_newPostInTag_alert'];

    // Explain what following does to someone who doesn't follow the tag yet,
    // and would be notified if they did
    if (subscription !== false || !(notifyEmail || notifyAlert)) {
      return button;
    }

    const tooltipText = extractText(
      app.translator.trans(
        notifyEmail ? 'fof-follow-tags.forum.sub_controls.notify_email_tooltip' : 'fof-follow-tags.forum.sub_controls.notify_alert_tooltip'
      )
    );

    return (
      <Tooltip text={tooltipText} position={this.attrs.tooltipPosition || 'bottom'} delay={250}>
        {button}
      </Tooltip>
    );
  }
}

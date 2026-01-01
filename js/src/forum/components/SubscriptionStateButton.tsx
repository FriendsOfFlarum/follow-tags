import app from 'flarum/forum/app';
import Button, { IButtonAttrs } from 'flarum/common/components/Button';
import subscriptionOptions from '../utils/subscriptionOptions';
import Tooltip from 'flarum/common/components/Tooltip';
import extractText from 'flarum/common/utils/extractText';
import Stream from 'flarum/common/utils/Stream';
import type Mithril from 'mithril';

export interface ISubscriptionStateButtonAttrs extends IButtonAttrs {
  subscription?: string | false;
  className?: string;
}

export default class SubscriptionStateButton extends Button<ISubscriptionStateButtonAttrs> {
  loading!: Stream<boolean>;
  canShowTooltip!: Stream<boolean | undefined>;

  oninit(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>): void {
    super.oninit(vnode);

    this.loading = Stream(false);
    this.canShowTooltip = Stream(false);
  }

  onbeforeupdate(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>): void {
    super.onbeforeupdate(vnode);

    const subscription = this.attrs.subscription || false;

    const preferences = app.session.user?.preferences();
    const notifyEmail = preferences?.['notify_newPostInTag_email'];
    const notifyAlert = preferences?.['notify_newPostInTag_alert'];

    if ((notifyEmail || notifyAlert) && subscription === false) {
      this.canShowTooltip(undefined);
    } else {
      this.canShowTooltip(false);
    }
  }

  view(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>) {
    const subscription = this.attrs.subscription || false;
    const option = subscriptionOptions.find((opt) => opt.subscription === subscription);

    const buttonIcon = option ? option.icon : 'fas fa-star';
    const buttonLabel = option ? app.translator.trans(option.labelKey) : app.translator.trans('fof-follow-tags.forum.sub_controls.follow_button');

    this.attrs.className = (this.attrs.className || '') + ' SubscriptionButton ' + 'SubscriptionButton--' + subscription;
    this.attrs.icon = buttonIcon;

    const preferences = app.session.user?.preferences();
    const notifyEmail = preferences?.['notify_newPostInTag_email'];

    const tooltipText = extractText(
      app.translator.trans(
        notifyEmail ? 'fof-follow-tags.forum.sub_controls.notify_email_tooltip' : 'fof-follow-tags.forum.sub_controls.notify_alert_tooltip'
      )
    );

    return (
      <Tooltip
        text={
          // By adding this constraint we make sure that when we explicitly set the
          // value of `canShowTooltip` to `false`, the internal `shouldRecreateTooltip`
          // property of the `Tooltip` component is set to `true` and we can, in that
          // way, switch between 'manual' and 'hover' trigger modes for the tooltip.
          typeof this.canShowTooltip() === 'boolean' ? '' : tooltipText
        }
        tooltipVisible={this.canShowTooltip()}
        position="bottom"
        delay={250}
      >
        {super.view(Object.assign({}, vnode, { children: buttonLabel }))}
      </Tooltip>
    );
  }
}

import app from 'flarum/forum/app';
import Modal, { IInternalModalAttrs } from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import ItemList from 'flarum/common/utils/ItemList';
import { utils } from '../utils';
import SubscriptionOptionItem from './SubscriptionOptionItem';
import type Mithril from 'mithril';
import Tag from 'ext:flarum/tags/common/models/Tag';
import Stream from 'flarum/common/utils/Stream';
import Tooltip from 'flarum/common/components/Tooltip';

interface ISubscriptionModalAttrs extends IInternalModalAttrs {
  model?: Tag;
}

export default class SubscriptionModal extends Modal<ISubscriptionModalAttrs> {
  subscription!: string;
  loading: Stream<boolean>;
  canShowTooltip: boolean | undefined = undefined;

  oninit(vnode: Mithril.Vnode<ISubscriptionModalAttrs, this>): void {
    super.oninit(vnode);
    this.loading = Stream(false);
    this.subscription = this.attrs.model.subscription() || 'not_follow';

    const preferences = app.session.user?.preferences();
    const notifyEmail = preferences['notify_newPostInTag_email'];
    const notifyAlert = preferences['notify_newPostInTag_alert'];

    if ((notifyEmail || notifyAlert) && this.subscription === 'not_follow') {
      this.canShowTooltip = true;
    } else {
      this.canShowTooltip = false;
    }
  }

  className() {
    return 'SubscriptionModal Modal--medium';
  }

  title() {
    return app.translator.trans('fof-follow-tags.forum.sub_controls.header', {
      tagName: this.attrs.model.name(),
    });
  }

  content() {
    const preferences = app.session.user?.preferences();
    const notifyEmail = preferences['notify_newPostInTag_email'];
    const notifyAlert = preferences['notify_newPostInTag_alert'];

    return (
      <div className="Modal-body">
        {this.formOptionItems().toArray()}
        <div className="Form-group">
          <Button className="Button Button--primary" onclick={() => this.saveSubscription(this.subscription)} loading={this.loading()}>
            {app.translator.trans('fof-follow-tags.forum.sub_controls.submit_button')}
          </Button>
        </div>
      </div>
    );
  }

  formOptionItems(): ItemList<Mithril.Children> {
    const items = new ItemList<Mithril.Children>();

    items.add(
      'subscription_type',
      <div className="Form-group">
        <label>{app.translator.trans('fof-follow-tags.forum.sub_controls.subscription_label', { tagName: this.attrs.model.name() })}</label>
        <div className="SubscriptionModal-options">{this.subscriptionOptionItems().toArray()}</div>
      </div>,
      60
    );

    return items;
  }

  subscriptionOptionItems(): ItemList<Mithril.Children> {
    const items = new ItemList<Mithril.Children>();
    let priority = 100;

    utils.subscriptionOptions.forEach((option, index) => {
      const attrs = {
        ...option,
        onclick: () => {
          this.subscription = option.subscription;
          this.canShowTooltip = false;
        },
        active: this.subscription === option.subscription,
        disabled: option.subscription === 'hide' && this.attrs.model.isHidden(),
      };

      items.add(`subscription-option-${index}`, <SubscriptionOptionItem {...attrs} />, priority);
      priority -= 5;
    });

    return items;
  }

  saveSubscription(subscription: string) {
    const tag = this.attrs.model;

    this.loading(true);

    this.subscription = subscription;

    app
      .request({
        url: `${app.forum.attribute('apiUrl')}/tags/${tag.id()}/subscription`,
        method: 'POST',
        body: {
          data: this.requestData(),
        },
      })
      .then((res: any) => app.store.pushPayload(res))
      .then(() => {
        this.loading(false);

        m.redraw();
        this.hide();
      });
  }

  requestData(): { [key: string]: string } {
    return { subscription: this.subscription };
  }
}

import app from 'flarum/forum/app';
import Icon from 'flarum/common/components/Icon';
import Component, { ComponentAttrs } from 'flarum/common/Component';

interface SubscriptionOptionItemAttrs extends ComponentAttrs {
  active: boolean;
  icon: string;
  labelKey: string;
  descriptionKey: string;
  onclick: () => void;
}

export default class SubscriptionOptionItem extends Component<SubscriptionOptionItemAttrs> {
  view() {
    const isSelected = this.attrs.active;

    return (
      <div className={`SubscriptionOption ${isSelected ? 'selected' : ''}`} onclick={this.attrs.onclick}>
        <Icon name={this.attrs.icon} className="SubscriptionOption-icon" />
        <span className="SubscriptionOption-title">{app.translator.trans(this.attrs.labelKey)}</span>
        <div className="SubscriptionOption-description">{app.translator.trans(this.attrs.descriptionKey)}</div>
        {isSelected && <Icon name="fas fa-check" className="SubscriptionOption-selectedIcon" />}
      </div>
    );
  }
}

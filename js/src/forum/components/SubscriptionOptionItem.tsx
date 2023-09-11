import app from 'flarum/forum/app';
import icon from 'flarum/common/helpers/icon';
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
        {icon(this.attrs.icon, { className: 'SubscriptionOption-icon' })}
        <span>{app.translator.trans(this.attrs.labelKey)}</span>
        <div>{app.translator.trans(this.attrs.descriptionKey)}</div>
        {isSelected && icon('fas fa-check', { className: 'SubscriptionOption-selectedIcon' })}
      </div>
    );
  }
}

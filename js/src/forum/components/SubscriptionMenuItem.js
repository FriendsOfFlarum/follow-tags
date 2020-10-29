import Component from 'flarum/Component';
import icon from 'flarum/helpers/icon';

export default class SubscriptionMenuItem extends Component {
    view() {
        const attrs = {
            onclick: this.attrs.onclick,
            disabled: this.attrs.disabled,
        };

        return (
            <button className={`SubscriptionMenuItem hasIcon ${this.attrs.disabled && 'disabled'}`} {...attrs}>
                {this.attrs.active ? icon('fas fa-check', { className: 'Button-icon' }) : ''}
                <span className="SubscriptionMenuItem-label">
                    {icon(this.attrs.icon, { className: 'Button-icon' })}
                    <strong>{this.attrs.label}</strong>
                    <span className="SubscriptionMenuItem-description">{this.attrs.description}</span>
                </span>
            </button>
        );
    }
}

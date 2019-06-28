import Component from 'flarum/Component';
import icon from 'flarum/helpers/icon';

export default class SubscriptionMenuItem extends Component {
    view() {
        const attrs = {
            onclick: this.props.onclick,
            disabled: this.props.disabled
        };

        return (
            <button className={`SubscriptionMenuItem hasIcon ${this.props.disabled && 'disabled'}`} {...attrs}>
                {this.props.active ? icon('fas fa-check', { className: 'Button-icon' }) : ''}
                <span className="SubscriptionMenuItem-label">
                    {icon(this.props.icon, { className: 'Button-icon' })}
                    <strong>{this.props.label}</strong>
                    <span className="SubscriptionMenuItem-description">{this.props.description}</span>
                </span>
            </button>
        );
    }
}

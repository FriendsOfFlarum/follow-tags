import Dropdown from 'flarum/components/Dropdown';
import Button from 'flarum/components/Button';
import icon from 'flarum/helpers/icon';
import extractText from 'flarum/utils/extractText';

import SubscriptionMenuItem from './SubscriptionMenuItem';
import icons from '../icons';

export default class SubscriptionMenu extends Dropdown {
    init() {
        this.loading = m.prop(false);

        this.options = [
            {
                subscription: false,
                icon: icons[false],
                label: app.translator.trans('fof-follow-tags.forum.sub_controls.not_following_button'),
                description: app.translator.trans('fof-follow-tags.forum.sub_controls.not_following_text'),
            },
            {
                subscription: 'follow',
                icon: icons.follow,
                label: app.translator.trans('fof-follow-tags.forum.sub_controls.following_button'),
                description: app.translator.trans('fof-follow-tags.forum.sub_controls.following_text'),
            },
            {
                subscription: 'lurk',
                icon: icons.lurk,
                label: app.translator.trans('fof-follow-tags.forum.sub_controls.lurking_button'),
                description: app.translator.trans('fof-follow-tags.forum.sub_controls.lurking_text'),
            },
            {
                subscription: 'ignore',
                icon: icons.ignore,
                label: app.translator.trans('fof-follow-tags.forum.sub_controls.ignoring_button'),
                description: app.translator.trans('fof-follow-tags.forum.sub_controls.ignoring_text'),
            },
            {
                subscription: 'hide',
                icon: icons.hide,
                label: app.translator.trans('fof-follow-tags.forum.sub_controls.hiding_button'),
                description: app.translator.trans('fof-follow-tags.forum.sub_controls.hiding_text'),
            },
        ];
    }

    view() {
        const tag = this.props.tag;
        const subscription = tag.subscription() || false;

        let buttonLabel = app.translator.trans('fof-follow-tags.forum.sub_controls.follow_button');
        let buttonIcon = icons[subscription] || 'far fa-star';
        const buttonClass = 'SubscriptionMenu-button--' + subscription;

        if (['follow', 'lurk', 'ignore', 'hide'].includes(subscription)) {
            const word = ['ignore', 'hide'].includes(subscription) ? subscription.slice(0, subscription.length - 1) : subscription;

            buttonLabel = app.translator.trans(`fof-follow-tags.forum.sub_controls.${word}ing_button`);
        }

        const preferences = app.session.user.preferences();
        const notifyEmail = preferences['notify_newPostInTag_email'];
        const notifyAlert = preferences['notify_newPostInTag_alert'];

        const title = extractText(
            app.translator.trans(
                notifyEmail ? 'fof-follow-tags.forum.sub_controls.notify_email_tooltip' : 'fof-follow-tags.forum.sub_controls.notify_alert_tooltip'
            )
        );

        const buttonProps = {
            className: 'Button SubscriptionMenu-button ' + buttonClass,
            icon: buttonIcon,
            children: buttonLabel,
            onclick: this.saveSubscription.bind(this, tag, ['follow', 'lurk', 'ignore', 'hide'].includes(subscription) ? false : 'follow'),
            title: title,
            loading: this.loading(),
        };

        if ((notifyEmail || notifyAlert) && subscription === false) {
            buttonProps.config = (element) => {
                $(element).tooltip({
                    container: '.SubscriptionMenu',
                    placement: 'bottom',
                    delay: 250,
                    title,
                });
            };
        } else {
            buttonProps.config = (element) => $(element).tooltip('destroy');
            delete buttonProps.title;
        }

        return (
            <div className="Dropdown ButtonGroup SubscriptionMenu App-primaryControl">
                {Button.component(buttonProps)}

                <button className={'Dropdown-toggle Button Button--icon ' + buttonClass} data-toggle="dropdown">
                    {icon('fas fa-caret-down', { className: 'Button-icon' })}
                </button>

                <ul className="Dropdown-menu dropdown-menu Dropdown-menu--right">
                    {this.options.map((props) => {
                        props.onclick = this.saveSubscription.bind(this, tag, props.subscription);
                        props.active = subscription === props.subscription;
                        props.disabled = props.subscription === 'hide' && tag.isHidden();

                        return <li>{SubscriptionMenuItem.component(props)}</li>;
                    })}
                </ul>
            </div>
        );
    }

    saveSubscription(tag, subscription) {
        this.loading(true);

        app.request({
            url: `${app.forum.attribute('apiUrl')}/tags/${tag.id()}/subscription`,
            method: 'POST',
            data: {
                data: {
                    subscription,
                },
            },
        })
            .then((res) => app.store.pushPayload(res))
            .then(() => {
                this.loading(false);
                m.lazyRedraw();
            });

        this.$('.SubscriptionMenu-button').tooltip('hide');
    }
}

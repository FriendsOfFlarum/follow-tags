import Button, { IButtonAttrs } from 'flarum/common/components/Button';
import Stream from 'flarum/common/utils/Stream';
import type Mithril from 'mithril';
export interface ISubscriptionStateButtonAttrs extends IButtonAttrs {
    subscription?: string | false;
    className?: string;
}
export default class SubscriptionStateButton extends Button<ISubscriptionStateButtonAttrs> {
    loading: Stream<boolean>;
    canShowTooltip: Stream<boolean | undefined>;
    oninit(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>): void;
    onbeforeupdate(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>): void;
    view(vnode: Mithril.Vnode<ISubscriptionStateButtonAttrs, this>): JSX.Element;
}

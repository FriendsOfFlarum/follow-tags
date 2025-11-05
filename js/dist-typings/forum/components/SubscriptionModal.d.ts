import Modal, { IInternalModalAttrs } from 'flarum/common/components/Modal';
import ItemList from 'flarum/common/utils/ItemList';
import type Mithril from 'mithril';
import type Tag from 'ext:flarum/tags/common/models/Tag';
import Stream from 'flarum/common/utils/Stream';
interface ISubscriptionModalAttrs extends IInternalModalAttrs {
    model: Tag;
}
export default class SubscriptionModal extends Modal<ISubscriptionModalAttrs> {
    subscription: string;
    loading: Stream<boolean>;
    canShowTooltip: boolean | undefined;
    oninit(vnode: Mithril.Vnode<ISubscriptionModalAttrs, this>): void;
    className(): string;
    title(): any[];
    content(): JSX.Element;
    formOptionItems(): ItemList<Mithril.Children>;
    subscriptionOptionItems(): ItemList<Mithril.Children>;
    saveSubscription(subscription: string): void;
}
export {};

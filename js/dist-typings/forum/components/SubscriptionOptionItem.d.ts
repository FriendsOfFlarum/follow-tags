/// <reference types="mithril" />
import Component, { ComponentAttrs } from 'flarum/common/Component';
interface SubscriptionOptionItemAttrs extends ComponentAttrs {
    active: boolean;
    icon: string;
    labelKey: string;
    descriptionKey: string;
    onclick: () => void;
}
export default class SubscriptionOptionItem extends Component<SubscriptionOptionItemAttrs> {
    view(): JSX.Element;
}
export {};

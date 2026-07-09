/// <reference types="mithril" />
import Modal, { IInternalModalAttrs } from 'flarum/common/components/Modal';
import Tag from 'ext:flarum/tags/common/models/Tag';
interface IChooseTagsToFollowModalAttrs extends IInternalModalAttrs {
    hasNotChosenYet?: boolean;
}
export default class ChooseTagsToFollowModal extends Modal<IChooseTagsToFollowModalAttrs> {
    loading: boolean;
    className(): string;
    title(): string | any[];
    content(): JSX.Element[];
    openSubscriptionModal(tag: Tag): void;
    continueToForum(): void;
}
export {};

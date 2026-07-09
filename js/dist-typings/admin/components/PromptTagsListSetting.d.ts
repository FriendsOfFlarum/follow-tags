import Component from 'flarum/common/Component';
import type Stream from 'flarum/common/utils/Stream';
import type Mithril from 'mithril';
import Tag from 'ext:flarum/tags/common/models/Tag';
interface IPromptTagsListSettingAttrs {
    setting: Stream<string>;
}
export default class PromptTagsListSetting extends Component<IPromptTagsListSettingAttrs> {
    loading: boolean;
    oninit(vnode: Mithril.Vnode<IPromptTagsListSettingAttrs, this>): void;
    view(): JSX.Element | JSX.Element[];
    tagIds(): string[];
    toggle(tag: Tag, value: boolean): void;
}
export {};

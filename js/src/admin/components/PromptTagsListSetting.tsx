import app from 'flarum/admin/app';
import Component from 'flarum/common/Component';
import Switch from 'flarum/common/components/Switch';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import Stream from 'flarum/common/utils/Stream';
import sortTags from 'flarum/tags/common/utils/sortTags';
import type Mithril from 'mithril';
import Tag from 'flarum/tags/models/Tag';

interface IPromptTagsListSettingAttrs {
  setting: Stream<string>;
}

export default class PromptTagsListSetting extends Component<IPromptTagsListSettingAttrs> {
  loading = true;

  oninit(vnode: Mithril.Vnode<IPromptTagsListSettingAttrs, this>) {
    super.oninit(vnode);

    // Only the primary tags are loaded by default, so we need to make the same
    // request the tags page settings does to load the full list
    app.store.find('tags', { include: 'parent' }).then(() => {
      this.loading = false;

      m.redraw();
    });
  }

  view() {
    if (this.loading) {
      return <LoadingIndicator />;
    }

    return sortTags(app.store.all<Tag>('tags')).map((tag) => (
      <div className="Form-group">
        <Switch state={this.tagIds().includes(tag.id()!)} onchange={(value: boolean) => this.toggle(tag, value)}>
          {tag.name()}
        </Switch>
      </div>
    ));
  }

  tagIds(): string[] {
    let tagIds;

    try {
      tagIds = JSON.parse(this.attrs.setting() || '[]');
    } catch (e) {
      tagIds = [];
    }

    return Array.isArray(tagIds) ? tagIds : [];
  }

  toggle(tag: Tag, value: boolean) {
    const tagIds = this.tagIds();

    this.attrs.setting(JSON.stringify(value ? [...tagIds, tag.id()] : tagIds.filter((id) => id !== tag.id())));
  }
}

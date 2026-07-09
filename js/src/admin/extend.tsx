import app from 'flarum/admin/app';
import Extend from 'flarum/common/extenders';
import type ExtensionPage from 'flarum/admin/components/ExtensionPage';
import type Mithril from 'mithril';
import followingPageOptions from '../common/utils/followingPageOptions';
import PromptTagsListSetting from './components/PromptTagsListSetting';

const translationPrefix = 'fof-follow-tags.admin.settings.';

export default [
  new Extend.Admin()
    .setting(() => ({
      setting: 'fof-follow-tags.following_page_default',
      options: followingPageOptions('admin.settings'),
      type: 'select',
      label: app.translator.trans(translationPrefix + 'following_page_default_label'),
    }))
    .setting(() => ({
      setting: 'fof-follow-tags.prompt_new_users',
      type: 'boolean',
      label: app.translator.trans(translationPrefix + 'prompt_new_users_label'),
      help: app.translator.trans(translationPrefix + 'prompt_new_users_help'),
    }))
    .setting(() => ({
      setting: 'fof-follow-tags.prompt_button_on_following_page',
      type: 'boolean',
      label: app.translator.trans(translationPrefix + 'prompt_button_on_following_page_label'),
    }))
    .setting(() => ({
      setting: 'fof-follow-tags.prompt_tag_strategy',
      type: 'select',
      options: {
        primary: app.translator.trans(translationPrefix + 'prompt_tag_strategy_options.primary'),
        primaryAndChildren: app.translator.trans(translationPrefix + 'prompt_tag_strategy_options.primaryAndChildren'),
        primaryAndSecondary: app.translator.trans(translationPrefix + 'prompt_tag_strategy_options.primaryAndSecondary'),
        secondary: app.translator.trans(translationPrefix + 'prompt_tag_strategy_options.secondary'),
        all: app.translator.trans(translationPrefix + 'prompt_tag_strategy_options.all'),
        list: app.translator.trans(translationPrefix + 'prompt_tag_strategy_options.list'),
      },
      default: 'primary',
      label: app.translator.trans(translationPrefix + 'prompt_tag_strategy_label'),
    }))
    .customSetting(function (this: ExtensionPage) {
      if (this.setting('fof-follow-tags.prompt_tag_strategy')() !== 'list') return null;

      return (
        <div className="Form-group">
          <label>{app.translator.trans(translationPrefix + 'prompt_tag_ids_label')}</label>
          <PromptTagsListSetting setting={this.setting('fof-follow-tags.prompt_tag_ids')} />
        </div>
      );
    } as () => Mithril.Children)
    .setting(() => ({
      setting: 'fof-follow-tags.all_discussions_on_following_page_for_guests',
      type: 'boolean',
      label: app.translator.trans(translationPrefix + 'all_discussions_on_following_page_for_guests_label'),
    })),
];

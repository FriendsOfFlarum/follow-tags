import app from 'flarum/admin/app';
import { extend } from 'flarum/common/extend';
import BasicsPage from 'flarum/admin/components/BasicsPage';
import type ExtensionPage from 'flarum/admin/components/ExtensionPage';
import type ItemList from 'flarum/common/utils/ItemList';
import followingPageOptions from '../common/utils/followingPageOptions';
import PromptTagsListSetting from './components/PromptTagsListSetting';

const translationPrefix = 'fof-follow-tags.admin.settings.';

app.initializers.add('fof/follow-tags', () => {
  app.extensionData
    .for('fof-follow-tags')
    .registerSetting({
      setting: 'fof-follow-tags.following_page_default',
      options: followingPageOptions('admin.settings'),
      type: 'select',
      label: app.translator.trans(translationPrefix + 'following_page_default_label'),
      default: 'none',
      required: true,
    })
    .registerSetting({
      setting: 'fof-follow-tags.prompt_new_users',
      type: 'boolean',
      label: app.translator.trans(translationPrefix + 'prompt_new_users_label'),
      help: app.translator.trans(translationPrefix + 'prompt_new_users_help'),
    })
    .registerSetting({
      setting: 'fof-follow-tags.prompt_button_on_following_page',
      type: 'boolean',
      label: app.translator.trans(translationPrefix + 'prompt_button_on_following_page_label'),
    })
    .registerSetting({
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
    })
    .registerSetting(function (this: ExtensionPage) {
      if (this.setting('fof-follow-tags.prompt_tag_strategy')() !== 'list') return null;

      return (
        <div className="Form-group">
          <label>{app.translator.trans(translationPrefix + 'prompt_tag_ids_label')}</label>
          <PromptTagsListSetting setting={this.setting('fof-follow-tags.prompt_tag_ids')} />
        </div>
      );
    })
    .registerSetting({
      setting: 'fof-follow-tags.all_discussions_on_following_page_for_guests',
      type: 'boolean',
      label: app.translator.trans(translationPrefix + 'all_discussions_on_following_page_for_guests_label'),
    });

  // Because neither flarum/subscriptions nor fof/follow-tags does it, we offer
  // the Following page as a homepage option here
  extend(BasicsPage.prototype, 'homePageItems', function (items: ItemList<unknown>) {
    items.add('following', {
      path: '/following',
      label: app.translator.trans('fof-follow-tags.admin.basics.following_label'),
    });
  });
});

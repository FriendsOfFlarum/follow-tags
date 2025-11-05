import app from 'flarum/admin/app';
import followingPageOptions from '../common/utils/followingPageOptions';

app.initializers.add('fof/follow-tags', () => {
  app.registry.for('fof-follow-tags').registerSetting({
    setting: 'fof-follow-tags.following_page_default',
    options: followingPageOptions('admin.settings'),
    type: 'select',
    label: app.translator.trans('fof-follow-tags.admin.settings.following_page_default_label'),
    default: 'none',
    required: true,
  });
});

import app from 'flarum/admin/app';
import Extend from 'flarum/common/extenders';
import followingPageOptions from '../common/utils/followingPageOptions';

export default [
  new Extend.Admin().setting(() => ({
    setting: 'fof-follow-tags.following_page_default',
    options: followingPageOptions('admin.settings'),
    type: 'select',
    label: app.translator.trans('fof-follow-tags.admin.settings.following_page_default_label'),
    default: 'none',
    required: true,
  })),
];

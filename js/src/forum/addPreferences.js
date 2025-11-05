import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';

import FieldSet from 'flarum/common/components/FieldSet';
import Select from 'flarum/common/components/Select';

import { getOptions, getDefaultFollowingFiltering } from './utils/getDefaultFollowingFiltering';

export default () => {
  extend('flarum/forum/components/SettingsPage', 'settingsItems', function (items) {
    items.add(
      'fof-follow-tags',
      FieldSet.component(
        {
          label: app.translator.trans('fof-follow-tags.forum.user.settings.heading'),
          className: 'Settings-follow-tags',
        },
        [
          <div className="Form-group">
            <p>{app.translator.trans('fof-follow-tags.forum.user.settings.filter_label')}</p>
            {Select.component({
              options: getOptions(),
              value: this.user.preferences().followTagsPageDefault || getDefaultFollowingFiltering(),
              onchange: (value) => {
                this.user.savePreferences({ followTagsPageDefault: value }).then(() => {
                  m.redraw();
                });
              },
            })}
          </div>,
        ]
      )
    );
  });
};

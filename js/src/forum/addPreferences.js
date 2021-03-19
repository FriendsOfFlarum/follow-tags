import { extend } from 'flarum/extend';

import SettingsPage from 'flarum/components/SettingsPage';
import FieldSet from 'flarum/components/FieldSet';
import Select from 'flarum/components/Select';

import { getOptions, getDefaultFollowingFiltering } from './utils/getDefaultFollowingFiltering';

export default () => {
    extend(SettingsPage.prototype, 'settingsItems', function (items) {
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

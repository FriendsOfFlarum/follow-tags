import { settings } from '@fof-components';
import followingPageOptions from '../common/utils/followingPageOptions';

const {
    SettingsModal,
    items: { SelectItem },
} = settings;

app.initializers.add('fof/follow-tags', () => {
    app.extensionSettings['fof-follow-tags'] = () =>
        app.modal.show(SettingsModal, {
            title: app.translator.trans('fof-follow-tags.admin.settings.title'),
            type: 'small',
            items: (setting) => [
                <div className="Form-group">
                    <label>{app.translator.trans('fof-follow-tags.admin.settings.following_page_default_label')}</label>

                    {SelectItem.component({
                        options: followingPageOptions('admin.settings'),
                        name: 'fof-follow-tags.following_page_default',
                        setting,
                        default: 'none',
                        required: true,
                    })}
                </div>,
            ],
        });
});

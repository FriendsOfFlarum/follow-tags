import Component from 'flarum/Component';

import Button from 'flarum/components/Button';
import Dropdown from 'flarum/components/Dropdown';

import { getOptions } from '../utils/getDefaultFollowingFiltering';

export default class FollowingPageFilterDropdown extends Component {
    view() {
        const selected = app.discussions.followTags;
        const options = this.options();

        return Dropdown.component({
            buttonClassName: 'Button',
            label: options[selected] || getDefaultFollowingFiltering(),
        }, Object.keys(options).map((key) => {
            const active = key === selected;

            return Button.component({
                active,
                icon: active ? 'fas fa-check' : true,
                onclick: () => {
                    app.discussions.followTags = key;

                    app.discussions.refresh();
                },
            }, options[key]);
        }));
    }

    options() {
        return getOptions();
    }
}

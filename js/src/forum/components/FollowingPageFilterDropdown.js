import Component from 'flarum/Component';

import Button from 'flarum/components/Button';
import Dropdown from 'flarum/components/Dropdown';

import { getOptions } from '../utils/getDefaultFollowingFiltering';

export default class FollowingPageFilterDropdown extends Component {
    view() {
        const selected = app.cache.discussionList.followTags;
        const options = this.options();

        return Dropdown.component({
            buttonClassName: 'Button',
            label: options[selected] || getDefaultFollowingFiltering(),
            children: Object.keys(options).map((key) => {
                const active = key === selected;

                return Button.component({
                    active,
                    children: options[key],
                    icon: active ? 'fas fa-check' : true,
                    onclick: () => {
                        app.cache.discussionList.followTags = key;

                        app.cache.discussionList.refresh();
                    },
                });
            }),
        });
    }

    options() {
        return getOptions();
    }
}

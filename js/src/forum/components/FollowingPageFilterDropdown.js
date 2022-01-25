import app from 'flarum/forum/app';
import Component from 'flarum/common/Component';

import Button from 'flarum/common/components/Button';
import Dropdown from 'flarum/common/components/Dropdown';

import { getDefaultFollowingFiltering, getOptions } from '../utils/getDefaultFollowingFiltering';

export default class FollowingPageFilterDropdown extends Component {
  view() {
    const selected = app.discussions.followTags;
    const options = this.options();

    return Dropdown.component(
      {
        buttonClassName: 'Button',
        label: options[selected] || getDefaultFollowingFiltering(),
      },
      Object.keys(options).map((key) => {
        const active = key === selected;

        return Button.component(
          {
            active,
            icon: active ? 'fas fa-check' : true,
            onclick: () => {
              app.discussions.followTags = key;

              app.discussions.refresh();
            },
          },
          options[key]
        );
      })
    );
  }

  options() {
    return getOptions();
  }
}

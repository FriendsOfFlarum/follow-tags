import app from 'flarum/forum/app';
import Component from 'flarum/common/Component';

import Button from 'flarum/common/components/Button';
import Dropdown from 'flarum/common/components/Dropdown';
import type Mithril from 'mithril';

import { getDefaultFollowingFiltering, getOptions } from '../utils/getDefaultFollowingFiltering';

export default class FollowingPageFilterDropdown extends Component {
  view(): Mithril.Children {
    const selected = (app.discussions as any).followTags;
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
              (app.discussions as any).followTags = key;

              app.discussions.refresh();
            },
          },
          options[key]
        );
      })
    );
  }

  options(): { [key: string]: string | any[] } {
    return getOptions();
  }
}

import Component from 'flarum/common/Component';
import type Mithril from 'mithril';
export default class FollowingPageFilterDropdown extends Component {
    view(): Mithril.Children;
    options(): {
        [key: string]: string | any[];
    };
}

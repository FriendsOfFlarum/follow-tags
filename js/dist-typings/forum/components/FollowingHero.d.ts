import Component from 'flarum/common/Component';
import ItemList from 'flarum/common/utils/ItemList';
import type Mithril from 'mithril';
export default class FollowingHero extends Component {
    view(): JSX.Element;
    viewItems(): ItemList<Mithril.Children>;
    contentItems(): ItemList<Mithril.Children>;
    heroColor(): string | null;
    heroIcon(): string;
}

import Notification from 'flarum/components/Notification';

import icons from '../icons';

export default class NewDiscussionNotification extends Notification {
    icon() {
        return icons.follow;
    }

    href() {
        const notification = this.props.notification;
        const discussion = notification.subject();

        return app.route.discussion(discussion);
    }

    content() {
        return app.translator.trans('fof-follow-tags.forum.notifications.new_discussion_text', {
            user: this.props.notification.fromUser(),
            title: this.props.notification.subject().title(),
        });
    }
}

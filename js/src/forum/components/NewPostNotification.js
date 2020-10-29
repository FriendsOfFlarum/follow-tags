import Notification from 'flarum/components/Notification';

import icons from '../icons';

export default class NewPostNotification extends Notification {
    icon() {
        return icons.lurk;
    }

    href() {
        const notification = this.attrs.notification;
        const discussion = notification.subject();
        const content = notification.content() || {};

        return app.route.discussion(discussion, content.postNumber);
    }

    content() {
        return app.translator.trans('fof-follow-tags.forum.notifications.new_post_text', { user: this.attrs.notification.fromUser() });
    }
}

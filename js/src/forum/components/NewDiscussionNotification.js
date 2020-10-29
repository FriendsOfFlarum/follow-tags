import Notification from 'flarum/components/Notification';

export default class NewDiscussionNotification extends Notification {
    icon() {
        return 'fas fa-user-tag';
    }

    href() {
        const notification = this.attrs.notification;
        const discussion = notification.subject();

        return app.route.discussion(discussion);
    }

    content() {
        return app.translator.trans('fof-follow-tags.forum.notifications.new_discussion_text', {
            user: this.attrs.notification.fromUser(),
            title: this.attrs.notification.subject().title(),
        });
    }
}

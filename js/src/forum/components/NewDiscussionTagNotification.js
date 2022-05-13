import app from 'flarum/forum/app';
import Notification from 'flarum/forum/components/Notification';

export default class NewDiscussionTagNotification extends Notification {
  icon() {
    return 'fas fa-user-tag';
  }

  href() {
    const notification = this.attrs.notification;
    const discussion = notification.subject();

    return app.route.discussion(discussion);
  }

  content() {
    return app.translator.trans('fof-follow-tags.forum.notifications.new_discussion_tag_text', {
      user: this.attrs.notification.fromUser(),
      title: this.attrs.notification.subject().title(),
    });
  }

  excerpt() {
    return null;
  }
}

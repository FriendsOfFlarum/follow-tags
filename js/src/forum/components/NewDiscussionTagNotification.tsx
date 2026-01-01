import app from 'flarum/forum/app';
import Notification from 'flarum/forum/components/Notification';

export default class NewDiscussionTagNotification extends Notification {
  icon(): string {
    return 'fas fa-user-tag';
  }

  href(): string {
    const notification = this.attrs.notification;
    const discussion = notification.subject();

    return app.route.discussion(discussion as any);
  }

  content() {
    const subject = this.attrs.notification.subject();
    return app.translator.trans('fof-follow-tags.forum.notifications.new_discussion_tag_text', {
      user: this.attrs.notification.fromUser(),
      title: subject ? (subject as any).title() : '',
    });
  }

  excerpt(): null {
    return null;
  }
}

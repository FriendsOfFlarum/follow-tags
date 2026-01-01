import app from 'flarum/forum/app';
import Notification from 'flarum/forum/components/Notification';

import icons from '../icons';

export default class NewPostNotification extends Notification {
  icon(): string {
    return icons.lurk;
  }

  href(): string {
    const notification = this.attrs.notification;
    const discussion = notification.subject();
    const content: any = notification.content() || {};

    return app.route.discussion(discussion as any, content.postNumber);
  }

  content() {
    return app.translator.trans('fof-follow-tags.forum.notifications.new_post_text', { user: this.attrs.notification.fromUser() });
  }

  excerpt(): null {
    return null;
  }
}

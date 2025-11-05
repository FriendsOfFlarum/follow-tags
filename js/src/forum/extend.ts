import Extend from 'flarum/common/extenders';
import IndexPage from 'flarum/forum/components/IndexPage';

import commonExtend from '../common/extend';
import NewDiscussionNotification from './components/NewDiscussionNotification';
import NewPostNotification from './components/NewPostNotification';
import NewDiscussionTagNotification from './components/NewDiscussionTagNotification';

export default [
  ...commonExtend,

  new Extend.Routes() //
    .add('following', '/following', IndexPage),

  new Extend.Notification() //
    .add('newPostInTag', NewPostNotification) //
    .add('newDiscussionInTag', NewDiscussionNotification) //
    .add('newDiscussionTag', NewDiscussionTagNotification),
];

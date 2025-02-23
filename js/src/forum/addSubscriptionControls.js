import app from 'flarum/forum/app';
import Model from 'flarum/common/Model';

export default () => {
  app.store.models.tags.prototype.subscription = Model.attribute('subscription');
};

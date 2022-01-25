import app from 'flarum/forum/app';

export default () => m.route.get().includes(app.route('following'));

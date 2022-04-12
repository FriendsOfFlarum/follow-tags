import app from 'flarum/forum/app';

export default () => 'flarum-subscriptions' in flarum.extensions && m.route.get().includes(app.route('following'))

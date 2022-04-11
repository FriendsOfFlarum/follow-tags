import app from 'flarum/forum/app';

export default () => {
    if (!app.initializers.has('subscriptions')) return;

    return m.route.get().includes(app.route('following'));
}

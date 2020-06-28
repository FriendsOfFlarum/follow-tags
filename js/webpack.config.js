const merge = require('webpack-merge');
const config = require('flarum-webpack-config');

module.exports = merge(config({
  useExtensions: ['fof-components'],
}), {
    externals: [{
        'flarum/subscriptions/*': 'subscriptions/*'
    }]
});

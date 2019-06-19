const merge = require('webpack-merge');
const config = require('flarum-webpack-config');

module.exports = merge(config(), {
    externals: [{
        'flarum/subscriptions/*': 'subscriptions/*'
    }]
});
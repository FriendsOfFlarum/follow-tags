const path = require('path');
const config = require('@flarum/jest-config')();

// flarum/tags is a dependency, vendored beside core: the extension imports it
// as `ext:flarum/tags/...`, which the shared config leaves unmapped.
const tags = path.resolve(__dirname, '../vendor/flarum/tags/js/src');

module.exports = {
  ...config,
  roots: [...config.roots, tags],
  moduleNameMapper: {
    '^ext:flarum/tags/(.*)$': path.join(tags, '$1'),
    ...config.moduleNameMapper,
  },
};

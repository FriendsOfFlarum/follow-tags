import app from 'flarum/common/app';

const cache = {};

export default function followingPageOptions(section) {
  if (!cache[section]) {
    cache[section] = ['none', 'tags'].reduce((o, key) => {
      o[key] = app.translator.trans(`fof-follow-tags.${section}.following_${key}_label`);

      return o;
    }, {});
  }

  return cache[section];
}

import app from 'flarum/common/app';

type FollowingPageOptions = {
  [key: string]: string | any[];
};

const cache: Record<string, FollowingPageOptions> = {};

export default function followingPageOptions(section: string): FollowingPageOptions {
  if (!cache[section]) {
    cache[section] = ['none', 'tags'].reduce((o, key) => {
      o[key] = app.translator.trans(`fof-follow-tags.${section}.following_${key}_label`);

      return o;
    }, {} as FollowingPageOptions);
  }

  return cache[section];
}

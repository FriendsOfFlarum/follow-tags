import app from 'flarum/common/app';
import { optionProviders } from './addFollowingPageOption';

type FollowingPageOptions = {
  [key: string]: string | any[];
};

const cache: Record<string, FollowingPageOptions> = {};

export default function followingPageOptions(section: string): FollowingPageOptions {
  if (!cache[section]) {
    // Start with default options
    cache[section] = ['none', 'tags'].reduce((o, key) => {
      o[key] = app.translator.trans(`fof-follow-tags.${section}.following_${key}_label`);

      return o;
    }, {} as FollowingPageOptions);

    // Add options from other extensions
    optionProviders.forEach((provider) => {
      Object.assign(cache[section], provider(section));
    });
  }

  return cache[section];
}

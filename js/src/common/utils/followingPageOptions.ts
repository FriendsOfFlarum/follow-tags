import app from 'flarum/common/app';

type FollowingPageOptions = {
  [key: string]: string | any[];
};

const cache: Record<string, FollowingPageOptions> = {};

// Allow other extensions to add their own options
const optionProviders: Array<(section: string) => Record<string, string>> = [];

export function addFollowingPageOption(provider: (section: string) => Record<string, string>) {
  optionProviders.push(provider);
}

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

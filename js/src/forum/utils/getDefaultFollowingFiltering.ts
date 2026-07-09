import app from 'flarum/forum/app';
import followingPageOptions from '../../common/utils/followingPageOptions';

export let options: { [key: string]: string | any[] };

export const getOptions = (): { [key: string]: string | any[] } => {
  if (!options) {
    options = followingPageOptions('forum.index.following');
  }

  return options;
};

export const getDefaultFollowingFiltering = (): string => {
  getOptions();

  let value: string | null = app.forum.attribute<string>('fofFollowTagsFollowingPageDefault');

  if (value && !options[value]) {
    value = null;
  }

  if (app.session.user) {
    const preference = app.session.user.preferences()?.followTagsPageDefault;

    if (preference && options[preference]) {
      value = preference;
    }
  }

  return value || 'none';
};

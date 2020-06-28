import followingPageOptions from '../../common/utils/followingPageOptions';

export let options;

export const getOptions = () => {
    if (!options) {
        options = followingPageOptions('forum.index.following');
    }

    return options;
};

export const getDefaultFollowingFiltering = () => {
    getOptions();

    let value = app.data['fof-follow-tags.following_page_default'];

    if (!options[value]) {
        value = null;
    }

    if (app.session.user) {
        const preference = app.session.user.preferences().followTagsPageDefault;

        if (options[preference]) {
            value = preference;
        }
    }

    return value || 'none';
};

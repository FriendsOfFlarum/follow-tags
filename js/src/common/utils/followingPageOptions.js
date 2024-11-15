import app from 'flarum/common/app';

let opts;

export default function followingPageOptions(section) {
    return opts ||
    (opts = ['none', 'tags'].reduce((o, key) => {
        o[key] = app.translator.trans(`fof-follow-tags.${section}.following_${key}_label`);

        return o;
    }, {}));
}

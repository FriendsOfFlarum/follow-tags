let opts;

export default (section) =>
    opts ||
    (opts = ['none', 'tags'].reduce((o, key) => {
        o[key] = app.translator.trans(`fof-follow-tags.${section}.following_${key}_label`);

        return o;
    }, {}));

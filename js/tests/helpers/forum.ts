import bootstrapForum from '@flarum/jest-config/src/bootstrap/forum';
import app from 'flarum/forum/app';
import flatten from 'flat';
import jsYaml from 'js-yaml';
import fs from 'fs';
import path from 'path';
import tagsExtend from 'ext:flarum/tags/common/extend';
import Tag from 'ext:flarum/tags/common/models/Tag';
import extend from '../../src/forum/extend';

/**
 * This extension's translations, with `=> key` references resolved as the
 * backend does before sending them.
 */
export function translations(): Record<string, string> {
  // Jest runs from the extension's js directory.
  const locale = path.resolve(process.cwd(), '../resources/locale/en.yml');
  const flat = flatten(jsYaml.load(fs.readFileSync(locale, 'utf8')) as object) as Record<string, string>;
  // A reference to another extension's key (e.g. flarum-subscriptions) is left as it is.
  const resolve = (value: string): string => {
    const key = value.startsWith('=> ') ? value.slice(3) : null;

    return key && key in flat ? resolve(flat[key]) : value;
  };

  return Object.fromEntries(Object.entries(flat).map(([key, value]) => [key, resolve(value)]));
}

/**
 * The forum app with this extension's extenders and translations.
 */
export function bootForum(): void {
  bootstrapForum();

  // flarum/tags is a dependency, so it boots first, as it does on a forum.
  // The forum's extenders are typed for the forum app, bootExtensions for any app.
  app.bootExtensions({ 'flarum-tags': { extend: tagsExtend }, 'fof-follow-tags': { extend } } as never);
  app.boot();

  app.translator.addTranslations(translations());
}

export function makeTag(id: string, attributes: Record<string, unknown> = {}): Tag {
  app.store.pushPayload({
    data: { type: 'tags', id, attributes: { name: `Tag ${id}`, slug: `tag-${id}`, subscription: null, isHidden: false, ...attributes } },
  });

  return app.store.getById<Tag>('tags', id)!;
}

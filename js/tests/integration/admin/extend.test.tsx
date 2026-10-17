import bootstrapAdmin from '@flarum/jest-config/src/bootstrap/admin';
import app from 'flarum/admin/app';
import mq from 'mithril-query';
import { jest } from '@jest/globals';
import Stream from 'flarum/common/utils/Stream';
import tagsExtend from 'ext:flarum/tags/common/extend';
import extend from '../../../src/admin/extend';
import PromptTagsListSetting from '../../../src/admin/components/PromptTagsListSetting';

beforeAll(() => {
  bootstrapAdmin();

  // flarum/tags is a dependency, so it boots first, as it does on a forum.
  // The admin's extenders are typed for the admin app, bootExtensions for any app.
  app.bootExtensions({ 'flarum-tags': { extend: tagsExtend }, 'fof-follow-tags': { extend } } as never);
  app.boot();
});

describe("the extension's admin", () => {
  it('registers its settings, so the admin can search for them', () => {
    // The custom tag list setting is read from the extension page, where the strategy isn't a list.
    const page = { setting: () => Stream('primary') };
    const settings = (app.registry.getSettings('fof-follow-tags') ?? []).map((setting: any) => {
      const resolved = typeof setting === 'function' ? setting.call(page) : setting;

      return resolved && 'setting' in resolved ? resolved.setting : null;
    });

    expect(settings).toEqual(
      expect.arrayContaining([
        'fof-follow-tags.following_page_default',
        'fof-follow-tags.prompt_new_users',
        'fof-follow-tags.prompt_button_on_following_page',
        'fof-follow-tags.prompt_tag_strategy',
        'fof-follow-tags.all_discussions_on_following_page_for_guests',
      ])
    );
  });
});

describe('choosing the tags new users are prompted to follow', () => {
  beforeAll(() => {
    app.store.pushPayload({
      data: ['1', '2', '3'].map((id) => ({ type: 'tags', id, attributes: { name: `Tag ${id}`, slug: `tag-${id}`, position: Number(id) } })),
    });

    // The full tag list is requested when the setting opens; these are it.
    jest.spyOn(app.store, 'find').mockResolvedValue([] as never);
  });

  const setting = async (value: string) => {
    const stream = Stream(value);
    const out = mq(PromptTagsListSetting, { setting: stream });

    await Promise.resolve();
    out.redraw();

    return { stream, out };
  };

  /** Switches the tag at the given position in the list on or off, as the admin's click does. */
  const toggle = (out: ReturnType<typeof mq>, index: number, checked: boolean) => {
    const input = out.find('.Checkbox input')[index] as HTMLInputElement;

    // mithril-query renders into its own document, so the event comes from there.
    const change = input.ownerDocument.createEvent('Event');
    change.initEvent('change', true, true);

    input.checked = checked;
    input.dispatchEvent(change);
  };

  it('lists every tag, switched on when chosen', async () => {
    const { out } = await setting('["2"]');

    expect(out.find('.Checkbox')).toHaveLength(3);
    expect(out.find('.Checkbox.on')).toHaveLength(1);
    expect(out).toContainRaw('Tag 2');
  });

  it('adds and removes tags from the setting', async () => {
    const { stream, out } = await setting('["2"]');

    toggle(out, 0, true);
    expect(JSON.parse(stream())).toEqual(['2', '1']);

    toggle(out, 1, false);
    expect(JSON.parse(stream())).toEqual(['1']);
  });

  it('treats a missing or broken setting as no tags', async () => {
    expect((await setting('')).out.find('.Checkbox.on')).toHaveLength(0);
    expect((await setting('not json')).out.find('.Checkbox.on')).toHaveLength(0);
    expect((await setting('{"a":1}')).out.find('.Checkbox.on')).toHaveLength(0);
  });
});

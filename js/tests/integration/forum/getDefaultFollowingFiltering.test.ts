import app from 'flarum/forum/app';
import type User from 'flarum/common/models/User';
import addFollowingPageOption from '../../../src/common/utils/addFollowingPageOption';
import { getDefaultFollowingFiltering, getOptions } from '../../../src/forum/utils/getDefaultFollowingFiltering';
import { bootForum } from '../../helpers/forum';

beforeAll(() => {
  bootForum();

  // Options are gathered once, so other extensions add theirs before the first read.
  addFollowingPageOption((section) => ({ users: `${section} users` }));
});

afterEach(() => {
  app.session.user = null;
  app.forum.pushAttributes({ fofFollowTagsFollowingPageDefault: null });
});

function signIn(followTagsPageDefault: string | null): void {
  app.store.pushPayload({ data: { type: 'users', id: '7', attributes: { username: 'reader', preferences: { followTagsPageDefault } } } });
  app.session.user = app.store.getById<User>('users', '7')!;
}

describe('the filtering options of the following page', () => {
  it("are none, tags, and other extensions' options", () => {
    expect(getOptions()).toEqual({
      none: ['Followed Discussions'],
      tags: ['Followed Tags'],
      users: 'forum.index.following users',
    });
  });
});

describe('the default filtering of the following page', () => {
  it('is none when nothing is configured', () => {
    expect(getDefaultFollowingFiltering()).toBe('none');
  });

  it("is the forum's default", () => {
    app.forum.pushAttributes({ fofFollowTagsFollowingPageDefault: 'tags' });

    expect(getDefaultFollowingFiltering()).toBe('tags');
  });

  it("ignores a forum default that isn't an option", () => {
    app.forum.pushAttributes({ fofFollowTagsFollowingPageDefault: 'gone' });

    expect(getDefaultFollowingFiltering()).toBe('none');
  });

  it("prefers the reader's own choice to the forum's", () => {
    app.forum.pushAttributes({ fofFollowTagsFollowingPageDefault: 'tags' });
    signIn('users');

    expect(getDefaultFollowingFiltering()).toBe('users');
  });

  it("keeps the forum's default when the reader's choice isn't an option", () => {
    app.forum.pushAttributes({ fofFollowTagsFollowingPageDefault: 'tags' });
    signIn('gone');

    expect(getDefaultFollowingFiltering()).toBe('tags');
  });
});

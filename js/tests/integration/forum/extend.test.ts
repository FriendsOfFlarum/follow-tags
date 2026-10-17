import app from 'flarum/forum/app';
import { bootForum, makeTag } from '../../helpers/forum';

beforeAll(() => bootForum());

describe("the forum's extenders", () => {
  it("give tags the reader's subscription", () => {
    expect(makeTag('1', { subscription: 'follow' }).subscription()).toBe('follow');
    expect(makeTag('2').subscription()).toBeNull();
  });

  it('add the following page route', () => {
    expect(app.routes.following.path).toBe('/following');
  });

  it("register this extension's notifications", () => {
    expect(Object.keys(app.notificationComponents)).toEqual(expect.arrayContaining(['newPostInTag', 'newDiscussionInTag', 'newDiscussionTag']));
  });
});

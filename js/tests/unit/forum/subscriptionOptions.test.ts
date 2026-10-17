import subscriptionOptions from '../../../src/forum/utils/subscriptionOptions';
import { translations } from '../../helpers/forum';

describe('the subscription options offered', () => {
  it('are the ones the backend accepts, plus not following, in order', () => {
    expect(subscriptionOptions.map((option) => option.subscription)).toEqual(['not_follow', 'follow', 'lurk', 'ignore', 'hide']);
  });

  it('each have a translated label and description', () => {
    const strings = translations();

    subscriptionOptions.forEach((option) => {
      expect(strings[option.labelKey]).toEqual(expect.any(String));
      expect(strings[option.descriptionKey]).toEqual(expect.any(String));
    });
  });
});

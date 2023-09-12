import { options, getOptions, getDefaultFollowingFiltering } from './getDefaultFollowingFiltering';
import isFollowingPage from './isFollowingPage';
import subscriptionOptions from './subscriptionOptions';
import { utils as commonUtils } from '../../common';

export const utils = {
  options,
  getOptions,
  getDefaultFollowingFiltering,
  isFollowingPage,
  subscriptionOptions,
  ...commonUtils,
};

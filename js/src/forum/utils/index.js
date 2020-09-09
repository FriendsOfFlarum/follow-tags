import {options, getOptions, getDefaultFollowingFiltering} from './getDefaultFollowingFiltering';
import isFollowingPage from './isFollowingPage';
import {utils as commonUtils} from '../../common';

export const utils = {
    options,
    getOptions,
    getDefaultFollowingFiltering,
    isFollowingPage,
    ...commonUtils,
};

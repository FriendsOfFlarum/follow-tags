declare const optionProviders: Array<(section: string) => Record<string, string | any[]>>;
export { optionProviders };
/**
 * Register a provider function that contributes options to the following page dropdown.
 * The provider will be called with a section name (e.g., "admin.settings", "forum.index_filter")
 * and should return an object mapping option keys to translated labels (or arrays for grouped options).
 */
export default function addFollowingPageOption(provider: (section: string) => Record<string, string | any[]>): void;

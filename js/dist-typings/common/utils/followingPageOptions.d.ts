type FollowingPageOptions = {
    [key: string]: string | any[];
};
export declare function addFollowingPageOption(provider: (section: string) => Record<string, string>): void;
export default function followingPageOptions(section: string): FollowingPageOptions;
export {};

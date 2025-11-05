import 'ext:flarum/tags/common/models/Tag';

declare module 'ext:flarum/tags/common/models/Tag' {
  export default interface Tag {
    subscription(): string | null;
  }
}

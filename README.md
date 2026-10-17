# Follow Tags by FriendsOfFlarum

![License](https://img.shields.io/badge/license-MIT-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/fof/follow-tags.svg)](https://packagist.org/packages/fof/follow-tags) [![OpenCollective](https://img.shields.io/badge/opencollective-fof-blue.svg)](https://opencollective.com/fof/donate)

A [Flarum](http://flarum.org) extension. Follow tags and be notified of new discussions.

### Features

- Follow, lurk, hide or ignore tags, and be notified of new discussions and replies.
- Optionally prompt new users to choose tags to follow after registration (off by default). The list of tags offered in the prompt is configurable, and the prompt can also be reopened from a button on the Following page.
- Optionally show all discussions on the Following page for guests, and offer the Following page as a homepage option (under Basics).

This extension includes the functionality previously provided by `clarkwinkelmann/flarum-ext-follow-tags-prompt`, and declares `replace` on that package so the two can never be installed at once. If you were using it, a regular `composer update` followed by `php flarum migrate && php flarum cache:clear` swaps it out and imports its settings and per-user state automatically; you can then remove the old package from your `composer.json` requirements with `composer remove clarkwinkelmann/flarum-ext-follow-tags-prompt`.

<details> 
  <summary>Screenshots </summary>
  
  <img src="https://i.imgur.com/BGJplYw.png" alt="share modal" width="300" />
</details>

### For developers

Other extensions can save their own fields alongside a user's tag subscription.

**Backend.** Users who cannot edit a tag may only send `subscription` when they `PATCH /api/tags/{id}`. To let them send your attribute with it, add the attribute to `TagResource` with a `writable()` check that refuses guests, then allow it:

```php
use FoF\FollowTags\Extend\Subscription;

return [
    (new Flarum\Extend\Conditional())
        ->whenExtensionEnabled('fof-follow-tags', fn () => [
            (new Subscription())->attribute('myAttribute'),
        ]),
];
```

Requests must still include `subscription`, and may not change the tag's own fields or relationships.

**Events.** `FoF\FollowTags\Event\SubscriptionChanging` is dispatched before a subscription is saved: listeners can change `$event->state`, and `$event->request` holds the request. `FoF\FollowTags\Event\SubscriptionChanged` is dispatched after it is saved.

**Frontend.** Extend `requestData()` on the subscription modal to send your attribute:

```js
import { extend } from 'flarum/common/extend';

extend('ext:fof/follow-tags/forum/components/SubscriptionModal', 'requestData', function (data) {
  data.myAttribute = this.myValue;
});
```

`formOptionItems()` is an `ItemList` for adding your own controls to the modal.

### Installation

Install with composer:

```sh
composer require fof/follow-tags:"*"
```

### Updating

```sh
composer update fof/follow-tags:"*"
```

### Links

[![OpenCollective](https://img.shields.io/badge/donate-friendsofflarum-44AEE5?style=for-the-badge&logo=open-collective)](https://opencollective.com/fof/donate) [![GitHub](https://img.shields.io/badge/donate-datitisev-ea4aaa?style=for-the-badge&logo=github)](https://datitisev.me/donate/github)

- [Discuss](https://discuss.flarum.org/d/20525)
- [Packagist](https://packagist.org/packages/fof/follow-tags)
- [GitHub](https://github.com/packages/FriendsOfFlarum/follow-tags)

An extension by [FriendsOfFlarum](https://github.com/FriendsOfFlarum).

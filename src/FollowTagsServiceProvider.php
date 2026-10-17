<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags;

use Flarum\Foundation\AbstractServiceProvider;

class FollowTagsServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // Attributes other extensions allow alongside a subscription, through
        // the Subscription extender. A singleton so extenders apply whichever
        // extension boots first.
        $this->container->singleton('fof-follow-tags.subscription-attributes', fn () => []);
    }
}

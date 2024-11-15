<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Event;

use Flarum\Tags\Tag;
use Flarum\Tags\TagState;
use Flarum\User\User;

class SubscriptionChanged
{
    public function __construct(
        public User $actor,
        public Tag $tag,
        public TagState $state
    ) {
    }
}

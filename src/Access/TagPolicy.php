<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Access;

use Flarum\Tags\Tag;
use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Model;

class TagPolicy extends AbstractPolicy
{
    /**
     * Allow registered users to "edit" tags for the purpose of updating their subscription.
     * The actual authorization for what fields can be updated is handled by the
     * API Resource field's writable() condition.
     */
    public function edit(User $actor, Tag $tag): ?string
    {
        // Allow registered users to update tags (the subscription field will be
        // the only writable field for them based on the API Resource definition)
        if (!$actor->isGuest()) {
            return $this->allow();
        }

        // Let other policies decide for guests
        return null;
    }
}

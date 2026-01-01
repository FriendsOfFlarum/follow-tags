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

class TagPolicy extends AbstractPolicy
{
    /**
     * Allow registered users to "edit" tags for the purpose of updating their subscription.
     * The actual authorization for what fields can be updated is handled by the
     * API Resource field's writable() condition.
     *
     * We use FORCE_ALLOW to override the core TagPolicy's DENY for restricted tags.
     */
    public function edit(User $actor, Tag $tag): ?string
    {
        // For restricted tags, check if the user has viewForum permission
        if ($tag->is_restricted && !$actor->isGuest()) {
            if ($actor->hasPermission("tag{$tag->id}.viewForum")) {
                // Use FORCE_ALLOW to override the core TagPolicy's DENY
                return $this->forceAllow();
            }
            // If they don't have viewForum permission, let other policies decide
            return null;
        }

        // For unrestricted tags, allow all registered users
        if (!$actor->isGuest()) {
            return $this->allow();
        }

        // Let other policies decide for guests
        return null;
    }
}

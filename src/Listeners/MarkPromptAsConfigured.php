<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Carbon\Carbon;
use Flarum\User\Event\Saving;
use Flarum\User\Exception\PermissionDeniedException;

class MarkPromptAsConfigured
{
    /**
     * @throws PermissionDeniedException
     */
    public function handle(Saving $event): void
    {
        if (!isset($event->data['attributes']['fofFollowTagsPromptConfigured'])) {
            return;
        }

        // Not using a permission because we want this to work even for
        // unconfirmed or suspended users.
        if ($event->actor->id !== $event->user->id) {
            throw new PermissionDeniedException();
        }

        if (is_null($event->user->fof_follow_tags_prompt_configured_at)) {
            $event->user->fof_follow_tags_prompt_configured_at = Carbon::now();
        }
    }
}

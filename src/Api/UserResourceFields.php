<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Api;

use Carbon\Carbon;
use Flarum\Api\Context;
use Flarum\Api\Schema;
use Flarum\User\User;

class UserResourceFields
{
    public function __invoke(): array
    {
        return [
            Schema\Boolean::make('fofFollowTagsPromptConfigured')
                ->hidden()
                // Not using a permission because we want this to work even for
                // unconfirmed or suspended users. Only the user themselves may
                // mark the prompt as configured, not even admins can.
                ->writable(fn (User $user, Context $context) => $context->updating() && $context->getActor()->id === $user->id)
                ->set(function (User $user, ?bool $value) {
                    if ($value && is_null($user->fof_follow_tags_prompt_configured_at)) {
                        $user->fof_follow_tags_prompt_configured_at = Carbon::now();
                    }
                }),
        ];
    }
}

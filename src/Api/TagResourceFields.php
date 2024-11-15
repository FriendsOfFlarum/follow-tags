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

use Flarum\Api\Schema;
use Flarum\Api\Context;
use Flarum\Tags\Tag;
use Flarum\Tags\TagState;
use Flarum\User\User;

class TagResourceFields
{
    public function __invoke(): array
    {
        return [
            Schema\Str::make('subscription')
                ->get(function (Tag $tag, Context $context) {
                    $state = $this->getStateFor($tag, $context->getActor());

                    return $state->subscription ?? null;
                })
        ];
    }

    /**
     * Get the *correct* state for the tag based on the actor.
     * If a state hasn't been already loaded OR the loaded state is not for the actor, load the correct state.
     *
     * @param Tag  $tag
     * @param User $actor
     *
     * @return TagState
     */
    public function getStateFor(Tag $tag, User $actor): TagState
    {
        // If $tag->state is loaded *and* null, this might be because the actor doesn't have tag-specific state
        // OR because the wrong actor has been used for loading it. `$tag->stateFor()` will return the correct state.
        // If it doesn't exist, it returns a dummy state with the correct actor & tag IDs.
        if (!$tag->relationLoaded('state') || is_null($tag->state) || $tag->state->user_id !== $actor->id) {
            $tag->setRelation('state', $tag->stateFor($actor));
        }

        return $tag->state;
    }
}

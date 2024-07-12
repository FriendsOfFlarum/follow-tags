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

use Flarum\Tags\Api\Serializer\TagSerializer;
use Flarum\Tags\Tag;
use Flarum\Tags\TagState;
use Flarum\User\User;

class AddTagSubscriptionAttribute
{
    public function __invoke(TagSerializer $serializer, Tag $tag, array $attributes): array
    {
        $state = $this->getStateFor($tag, $serializer->getActor());

        $attributes['subscription'] = $state->subscription ?? null;

        return $attributes;
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
        if (!$tag->relationLoaded('state') || $tag->state->user_id !== $actor->id) {
            $tag->setRelation('state', $tag->stateFor($actor));
        }

        return $tag->state;
    }
}

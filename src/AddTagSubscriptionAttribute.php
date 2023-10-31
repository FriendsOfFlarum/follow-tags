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

class AddTagSubscriptionAttribute
{
    public function __invoke(TagSerializer $serializer, Tag $tag, array $attributes): array
    {
        /** @var TagState $state */
        $state = $tag->stateFor($serializer->getActor());

        $attributes['subscription'] = $state->subscription;

        return $attributes;
    }
}

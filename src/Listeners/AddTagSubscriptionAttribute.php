<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2019 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Listeners;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Tags\Api\Serializer\TagSerializer;

class AddTagSubscriptionAttribute
{
    public function handle(Serializing $event)
    {
        if ($event->isSerializer(TagSerializer::class)
            && ($state = $event->model->stateFor($event->actor))) {
            $event->attributes['subscription'] = $state->subscription;
        }

        if ($event->isSerializer(DiscussionSerializer::class)) {
//            throw new \Error(json_encode($event->model->tags->map->state->map->subscription));
        }
    }
}

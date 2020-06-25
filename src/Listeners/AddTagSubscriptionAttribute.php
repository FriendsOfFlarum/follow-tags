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
use Flarum\Tags\Api\Serializer\TagSerializer;
use Illuminate\Support\Arr;

class AddTagSubscriptionAttribute
{
    public function handle(Serializing $event)
    {
        if ($event->isSerializer(TagSerializer::class)) {
            if (isset($event->model->state)) {
                $state = $event->model->state;
            } else {
                $state = $event->model->stateFor($event->actor);
            }

            $event->attributes['subscription'] = Arr::get($state ?? [], 'subscription');
        }
    }
}

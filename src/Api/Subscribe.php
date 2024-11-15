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

use Flarum\Api\Context;
use Flarum\Api\Endpoint\Endpoint;
use Flarum\Tags\TagState;
use FoF\FollowTags\Event;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class Subscribe extends Endpoint
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->route('POST', '{id}/subscription')
            ->authenticated()
            ->action($this->execute(...));
    }

    protected function execute(Context $context)
    {
        $actor = $context->getActor();
        $subscription = Arr::get($context->body(), 'data.subscription');

        $tag = $context->model;

        /** @var TagState $state */
        $state = $tag->stateFor($actor);

        if (! in_array($subscription, ['follow', 'lurk', 'ignore', 'hide'])) {
            $subscription = null;
        }

        $state->subscription = $subscription;

        $events = resolve(Dispatcher::class);

        $events->dispatch(new Event\SubscriptionChanging($actor, $tag, $state, $context->request));

        $state->save();

        $events->dispatch(new Event\SubscriptionChanged($actor, $tag, $state));

        return $tag;
    }
}

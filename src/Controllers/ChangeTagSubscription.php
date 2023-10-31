<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Controllers;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Flarum\Tags\Api\Serializer\TagSerializer;
use Flarum\Tags\Tag;
use Flarum\Tags\TagState;
use FoF\FollowTags\Event;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ChangeTagSubscription extends AbstractShowController
{
    public $serializer = TagSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $id = Arr::get($request->getQueryParams(), 'id');
        $subscription = Arr::get($request->getParsedBody(), 'data.subscription');

        $actor->assertRegistered();

        $tag = Tag::whereVisibleTo($actor)->findOrFail($id);

        /** @var TagState $state */
        $state = $tag->stateFor($actor);

        if (!in_array($subscription, ['follow', 'lurk', 'ignore', 'hide'])) {
            $subscription = null;
        }

        $state->subscription = $subscription;

        $this->events->dispatch(new Event\SubscriptionChanging($actor, $tag, $state, $request));

        $state->save();

        $this->events->dispatch(new Event\SubscriptionChanged($actor, $tag, $state));

        return $tag;
    }
}

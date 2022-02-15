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
use Flarum\User\User;
use FoF\FollowTags\Event\SubscriptionChanged;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ChangeTagSubscription extends AbstractShowController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = TagSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        /**
         * @var User
         */
        $actor = RequestUtil::getActor($request);
        $id = Arr::get($request->getQueryParams(), 'id');
        $subscription = Arr::get($request->getParsedBody(), 'data.subscription');

        $actor->assertRegistered();

        $tag = Tag::whereVisibleTo($actor)->findOrFail($id);
        $state = $tag->stateFor($actor);

        if (!in_array($subscription, ['follow', 'lurk', 'ignore', 'hide'])) {
            $subscription = null;
        }

        $state->subscription = $subscription;

        $state->save();

        $this->events->dispatch(new SubscriptionChanged($actor, $tag, $state));

        return $tag;
    }
}

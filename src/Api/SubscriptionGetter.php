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

use ArrayObject;
use Closure;
use Flarum\Api\Context;
use Flarum\Tags\Tag;
use Flarum\Tags\TagState;
use Flarum\User\User;
use WeakMap;

/**
 * Reads a tag's subscription for the actor. Tags included without the actor's
 * state loaded (a discussion's `tags.parent`, tags other extensions include)
 * are loaded together, one query for each level of includes, rather than one
 * query per tag.
 */
class SubscriptionGetter
{
    /**
     * Tags waiting for their state, for each request being serialized.
     *
     * @var WeakMap<object, ArrayObject<int, Tag>>
     */
    protected WeakMap $pending;

    public function __construct()
    {
        $this->pending = new WeakMap();
    }

    public function __invoke(Tag $tag, Context $context): Closure|string|null
    {
        $actor = $context->getActor();

        // A loaded state that stateFor() would use as it is, without a query.
        if ($tag->relationLoaded('state') && (!$tag->state || $tag->state->user_id === $actor->id)) {
            $tag->setRelation('state', $tag->stateFor($actor));

            return $tag->state->subscription;
        }

        // Guests have no states: their ID is 0, and every state belongs to a user.
        if ($actor->isGuest()) {
            $tag->setRelation('state', $this->newState($tag, $actor));

            return null;
        }

        $batch = $this->pending[$context->request] ??= new ArrayObject();
        $batch[spl_object_id($tag)] = $tag;

        // The serializer calls this once every tag on this level has been
        // queued, so the first call loads the whole batch.
        return function () use ($batch, $tag, $actor) {
            if (isset($batch[spl_object_id($tag)])) {
                $this->load($batch, $actor);
            }

            return $tag->state->subscription;
        };
    }

    /**
     * @param ArrayObject<int, Tag> $batch
     */
    protected function load(ArrayObject $batch, User $actor): void
    {
        $tags = $batch->getArrayCopy();
        $batch->exchangeArray([]);

        $states = TagState::query()
            ->where('user_id', $actor->id)
            ->whereIn('tag_id', array_unique(array_map(fn (Tag $tag) => $tag->id, $tags)))
            ->get()
            ->keyBy('tag_id');

        foreach ($tags as $tag) {
            $tag->setRelation('state', $states->get($tag->id) ?? $this->newState($tag, $actor));
        }
    }

    protected function newState(Tag $tag, User $actor): TagState
    {
        $state = new TagState();
        $state->tag_id = $tag->id;
        $state->user_id = $actor->id;

        return $state;
    }
}

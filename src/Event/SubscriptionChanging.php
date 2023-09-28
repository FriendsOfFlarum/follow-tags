<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Event;

use Flarum\Tags\Tag;
use Flarum\Tags\TagState;
use Flarum\User\User;
use Psr\Http\Message\ServerRequestInterface;

class SubscriptionChanging
{
    /**
     * @var User
     */
    public $actor;

    /**
     * @var Tag
     */
    public $tag;

    /**
     * @var TagState
     */
    public $state;

    /**
     * @var ServerRequestInterface
     */
    public $request;

    public function __construct(User $actor, Tag $tag, TagState $state, ServerRequestInterface $request)
    {
        $this->actor = $actor;
        $this->tag = $tag;
        $this->state = $state;
        $this->request = $request;
    }
}

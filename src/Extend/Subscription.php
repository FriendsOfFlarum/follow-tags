<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Extend;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Illuminate\Contracts\Container\Container;

class Subscription implements ExtenderInterface
{
    /** @var string[] */
    protected array $attributes = [];

    /**
     * Allow users who cannot edit a tag to save this TagResource attribute
     * alongside their subscription.
     *
     * The attribute's own `writable()` still decides who may write it, so
     * it must refuse guests and anyone else who may not.
     */
    public function attribute(string $name): self
    {
        $this->attributes[] = $name;

        return $this;
    }

    public function extend(Container $container, ?Extension $extension = null): void
    {
        $container->extend('fof-follow-tags.subscription-attributes', function (array $attributes) {
            return array_merge($attributes, $this->attributes);
        });
    }
}

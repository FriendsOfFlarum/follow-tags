<?php

namespace FoF\FollowTags\Extend;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Illuminate\Contracts\Container\Container;

class FollowTags implements ExtenderInterface
{
    private $newRejects = [];

    /**
     * Truthy values will be rejected from the notification.
     *
     * @param callable|string $callback
     *
     * The callable can be a closure or invokable class, and should accept:
     * - \Flarum\User\User $user: the user in question.
     * - \Flarum\Post\Post $post: the post being notified about.
     * - \Flarum\Database\Eloquent\Collection $tags: the tags assigned to the discussion.
     *
     * The callable should return:
     * - boolean: Indicating if the user should be rejected from the notification.
     *
     * @return self
     */
    public function reject($callback): self
    {
        $this->newRejects[] = $callback;

        return $this;
    }
    
    public function extend(Container $container, Extension $extension = null)
    {
        $container->extend('fof-follow-tags.rejects', function ($existingRejects) {
            return array_merge($existingRejects, $this->newRejects);
        });
    }
}

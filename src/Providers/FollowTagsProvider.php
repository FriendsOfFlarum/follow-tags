<?php

namespace FoF\FollowTags\Providers;

use Flarum\Database\Eloquent\Collection;
use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Post\Post;
use Flarum\User\User;
use Illuminate\Contracts\Container\Container;

class FollowTagsProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->bind('fof-follow-tags.notify.rejects', function (Container $container) {
            return [
                function (User $user, Post $post, Collection $tags): bool {
                    return $tags->map->stateFor($user)->map->subscription->contains('ignore')
                        || !$post->discussion->newQuery()->whereVisibleTo($user)->find($post->discussion->id)
                        || !$post->isVisibleTo($user);
                }
            ];
        });
    }
}

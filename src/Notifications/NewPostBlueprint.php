<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2019 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Notifications;

use Flarum\Discussion\Discussion;
use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Notification\MailableInterface;
use Flarum\Post\Post;

class NewPostBlueprint implements BlueprintInterface, MailableInterface
{
    /**
     * @var Post
     */
    public $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->post->discussion;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromUser()
    {
        return $this->post->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return ['postNumber' => (int) $this->post->number];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailView()
    {
        return ['text' => 'fof-follow-tags::emails.newPost'];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailSubject()
    {
        return app('translator')->trans('fof-follow-tags.email.newPostInTag', [
            'title' => $this->post->discussion->title,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getType()
    {
        return 'newPostInTag';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubjectModel()
    {
        return Discussion::class;
    }
}

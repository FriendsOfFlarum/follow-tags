<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Notifications;

use Flarum\Database\AbstractModel;
use Flarum\Locale\TranslatorInterface;
use Flarum\Notification\AlertableInterface;
use Flarum\Discussion\Discussion;
use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Notification\MailableInterface;
use Flarum\Post\Post;
use Flarum\User\User;

class NewPostBlueprint implements BlueprintInterface, MailableInterface, AlertableInterface
{
    public function __construct(
        public Post $post
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(): ?AbstractModel
    {
        return $this->post->discussion;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromUser(): ?User
    {
        return $this->post->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): mixed
    {
        return ['postNumber' => (int) $this->post->number];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailViews(): array
    {
        return ['text' => 'fof-follow-tags::email.plain.newPost', 'html' => 'fof-follow-tags::email.html.newPost'];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailSubject(TranslatorInterface $translator): string
    {
        return $translator->trans('fof-follow-tags.email.subject.newPostInTag', [
            'title' => $this->post->discussion->title,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return 'newPostInTag';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubjectModel(): string
    {
        return Discussion::class;
    }
}

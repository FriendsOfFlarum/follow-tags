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
use Flarum\Notification\AlertableInterface;
use Flarum\Discussion\Discussion;
use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Notification\MailableInterface;
use Flarum\Post\Post;
use Flarum\User\User;
use Flarum\Locale\TranslatorInterface;

class NewDiscussionBlueprint implements BlueprintInterface, MailableInterface, AlertableInterface
{
    public function __construct(
        public Discussion $discussion,
        public ?Post $post = null
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFromUser(): ?User
    {
        return $this->discussion->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(): ?AbstractModel
    {
        return $this->discussion;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): mixed
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailViews(): array
    {
        return ['text' => 'fof-follow-tags::email.plain.newDiscussion', 'html' => 'fof-follow-tags::email.html.newDiscussion'];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailSubject(TranslatorInterface $translator): string
    {
        return $translator->trans('fof-follow-tags.email.subject.newDiscussionInTag', [
            '{title' => $this->discussion->title,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return 'newDiscussionInTag';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubjectModel(): string
    {
        return Discussion::class;
    }
}

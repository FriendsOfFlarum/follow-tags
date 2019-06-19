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

class NewDiscussionBlueprint implements BlueprintInterface, MailableInterface
{
    public $discussion;

    public function __construct(Discussion $discussion)
    {
        $this->discussion = $discussion;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromUser()
    {
        return $this->discussion->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->discussion;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailView()
    {
        return ['text' => 'fof-follow-tags::emails.newDiscussion'];
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailSubject()
    {
        return app('translator')->trans('fof-follow-tags.email.newDiscussionInTag', [
            'title' => $this->discussion->title,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getType()
    {
        return 'newDiscussionInTag';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubjectModel()
    {
        return Discussion::class;
    }
}

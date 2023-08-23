<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Events;

use Flarum\Database\AbstractModel;
use Flarum\Database\Eloquent\Collection;

/**
 * Event fired when gathering recipients for a notification.
 *
 * Other extensions can listen to this event to modify the list of recipients
 * before notifications are sent.
 */
class GatheringRecipients
{
    /**
     * The model for which notifications should be sent.
     *
     * @var AbstractModel
     */
    public $model;

    /**
     * Collection of users who are set to receive the notification.
     *
     * This collection can be modified by listeners to add or remove users.
     *
     * @var Collection
     */
    public $recipients;

    /**
     * Create a new event instance.
     *
     * @param AbstractModel $model
     * @param Collection    &$recipients
     */
    public function __construct(AbstractModel $model, Collection &$recipients)
    {
        $this->model = $model;
        $this->recipients = &$recipients;
    }
}

<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Flarum\Database\Migration;

return Migration::addColumns('tag_user', [
    'created_at' => [
        'timestamp',
        'useCurrent' => true,
        'nullable'   => true,
    ],
    'updated_at' => [
        'timestamp',
        'useCurrent'         => true,
        'useCurrentOnUpdate' => true,
        'nullable'           => true,
    ],
]);

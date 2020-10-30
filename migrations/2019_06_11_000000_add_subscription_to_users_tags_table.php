<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Flarum\Database\Migration;

return Migration::addColumns('tag_user', [
    'subscription' => ['enum', 'allowed' => ['follow', 'lurk', 'ignore'], 'nullable' => true],
]);

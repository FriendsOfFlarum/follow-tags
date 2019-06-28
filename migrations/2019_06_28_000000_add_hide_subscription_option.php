<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) 2019 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $connection = $schema->getConnection();
        $prefix = $connection->getTablePrefix();

        $connection->statement("ALTER TABLE {$prefix}tag_user MODIFY COLUMN subscription ENUM('follow', 'lurk', 'ignore', 'hide')");
    },
    'down' => function (Builder $schema) {
        $connection = $schema->getConnection();
        $prefix = $connection->getTablePrefix();

        $connection->statement("ALTER TABLE {$prefix}tag_user MODIFY COLUMN subscription ENUM('follow', 'lurk', 'ignore')");
    },
];

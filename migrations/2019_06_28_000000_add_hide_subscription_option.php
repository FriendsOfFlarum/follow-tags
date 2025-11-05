<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $connection = $schema->getConnection();
        $prefix = $connection->getTablePrefix();
        $driver = $connection->getDriverName();

        // Only run for MySQL which uses ENUM type
        // For SQLite/PostgreSQL, the column is already a string that can hold any value
        if ($driver === 'mysql') {
            $connection->statement("ALTER TABLE {$prefix}tag_user MODIFY COLUMN subscription ENUM('follow', 'lurk', 'ignore', 'hide')");
        }
    },
    'down' => function (Builder $schema) {
        $connection = $schema->getConnection();
        $prefix = $connection->getTablePrefix();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $connection->statement("ALTER TABLE {$prefix}tag_user MODIFY COLUMN subscription ENUM('follow', 'lurk', 'ignore')");
        }
    },
];

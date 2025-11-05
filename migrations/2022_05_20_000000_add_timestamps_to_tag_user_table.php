<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        // Check if columns already exist before adding
        if (!$schema->hasColumn('tag_user', 'created_at')) {
            $schema->table('tag_user', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // Only set defaults for MySQL which supports timestamp defaults
        $connection = $schema->getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql' && $schema->hasColumn('tag_user', 'created_at')) {
            $prefix = $connection->getTablePrefix();
            $connection->statement("ALTER TABLE `{$prefix}tag_user` MODIFY created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
            $connection->statement("ALTER TABLE `{$prefix}tag_user` MODIFY updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        }
    },

    'down' => function (Builder $schema) {
        $schema->table('tag_user', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    },
];

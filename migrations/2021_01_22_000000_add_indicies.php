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
        $schema->table('tag_user', function (Blueprint $table) {
            $table->index(['user_id', 'subscription']);
            $table->index(['subscription']);
        });
    },

    'down' => function (Builder $schema) {
        $schema->table('tag_user', function (Blueprint $table) {
            $table->dropIndex(['subscription']);
            $table->dropIndex(['user_id', 'subscription']);
        });
    },
];

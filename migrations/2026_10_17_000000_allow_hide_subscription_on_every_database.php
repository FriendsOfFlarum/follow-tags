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

/*
 * The "hide" subscription was only ever allowed on MySQL. On PostgreSQL and
 * SQLite the subscription column checks its value against "follow", "lurk"
 * and "ignore", so choosing "hide" failed.
 */
$allow = function (Builder $schema, array $subscriptions): void {
    $connection = $schema->getConnection();

    if ($connection->getDriverName() === 'pgsql') {
        // The column's check constraint is named by PostgreSQL after the table.
        $table = $connection->getTablePrefix().'tag_user';
        $allowed = implode(', ', array_map(fn (string $subscription) => "'$subscription'", $subscriptions));

        $connection->statement("ALTER TABLE \"$table\" DROP CONSTRAINT IF EXISTS \"{$table}_subscription_check\"");
        $connection->statement("ALTER TABLE \"$table\" ADD CONSTRAINT \"{$table}_subscription_check\" CHECK (\"subscription\" IN ($allowed))");
    } elseif ($connection->getDriverName() === 'sqlite') {
        $schema->table('tag_user', function (Blueprint $table) use ($subscriptions) {
            $table->enum('subscription', $subscriptions)->nullable()->change();
        });
    }
};

return [
    'up' => function (Builder $schema) use ($allow) {
        $allow($schema, ['follow', 'lurk', 'ignore', 'hide']);
    },
    'down' => function (Builder $schema) use ($allow) {
        $allow($schema, ['follow', 'lurk', 'ignore']);
    },
];

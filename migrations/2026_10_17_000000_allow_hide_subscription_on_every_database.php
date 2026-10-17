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
 * The "hide" subscription was only allowed on databases using the "mysql"
 * driver. Elsewhere the subscription column only takes "follow", "lurk" and
 * "ignore": PostgreSQL and SQLite refused "hide", and MariaDB (a driver of its
 * own since Flarum 2) stored an empty value in its place.
 */
$allow = function (Builder $schema, array $subscriptions): void {
    $connection = $schema->getConnection();

    if ($connection->getDriverName() === 'pgsql') {
        // The column's check constraint is named by PostgreSQL after the table.
        $table = $connection->getTablePrefix().'tag_user';
        $allowed = implode(', ', array_map(fn (string $subscription) => "'$subscription'", $subscriptions));

        $connection->statement("ALTER TABLE \"$table\" DROP CONSTRAINT IF EXISTS \"{$table}_subscription_check\"");
        $connection->statement("ALTER TABLE \"$table\" ADD CONSTRAINT \"{$table}_subscription_check\" CHECK (\"subscription\" IN ($allowed))");
    } else {
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
        // On MySQL and MariaDB "hide" may well predate this migration (a forum
        // upgraded from Flarum 1 ran the 2019 one), so it is left in place.
        if (in_array($schema->getConnection()->getDriverName(), ['pgsql', 'sqlite'])) {
            $allow($schema, ['follow', 'lurk', 'ignore']);
        }
    },
];

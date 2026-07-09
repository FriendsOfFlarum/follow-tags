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

/*
 * Carry over data from clarkwinkelmann/flarum-ext-follow-tags-prompt, whose
 * functionality is now part of this extension. The old extension's column and
 * settings are left in place so it keeps working until it is uninstalled;
 * purging it will drop its own column.
 */
return [
    'up' => function (Builder $schema) {
        $db = $schema->getConnection();

        $settings = [
            'clarkwinkelmann-follow-tags-prompt.buttonOnFollowingPage'                   => 'fof-follow-tags.prompt_button_on_following_page',
            'clarkwinkelmann-follow-tags-prompt.allDiscussionsOnFollowingPageForGuests'  => 'fof-follow-tags.all_discussions_on_following_page_for_guests',
            'clarkwinkelmann-follow-tags-prompt.availableTagStrategy'                    => 'fof-follow-tags.prompt_tag_strategy',
            'clarkwinkelmann-follow-tags-prompt.availableTagIds'                         => 'fof-follow-tags.prompt_tag_ids',
        ];

        foreach ($settings as $old => $new) {
            $value = $db->table('settings')->where('key', $old)->value('value');

            if ($value !== null) {
                $db->table('settings')->insertOrIgnore([
                    'key'   => $new,
                    'value' => $value,
                ]);
            }
        }

        if ($schema->hasColumn('users', 'clarkwinkelmann_follow_tags_configured_at')) {
            $db->table('users')
                ->whereNull('fof_follow_tags_prompt_configured_at')
                ->update([
                    'fof_follow_tags_prompt_configured_at' => $db->raw('clarkwinkelmann_follow_tags_configured_at'),
                ]);

            // The prompt was always active in the standalone extension and the
            // Following page button defaulted to on, so preserve that behavior
            // for forums migrating from it. insertOrIgnore keeps any explicit
            // values imported above.
            $db->table('settings')->insertOrIgnore([
                ['key' => 'fof-follow-tags.prompt_new_users', 'value' => '1'],
                ['key' => 'fof-follow-tags.prompt_button_on_following_page', 'value' => '1'],
            ]);
        }
    },
    'down' => function (Builder $schema) {
        // The imported data is left in place under the new names.
    },
];

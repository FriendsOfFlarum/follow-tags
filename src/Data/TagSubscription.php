<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Data;

use Flarum\Gdpr\Data\Type;
use Flarum\Tags\Tag;
use Flarum\Tags\TagState;
use Illuminate\Support\Arr;

class TagSubscription extends Type
{
    public function export(): ?array
    {
        $exportData = [];

        Tag::query()
            ->each(function (Tag $tag) use (&$exportData) {
                $state = $tag->stateFor($this->user);
                $sanitized = $this->sanitize($state);

                // if the sanitized data has more than simply the `tag_id` key, we'll export it
                if (count($sanitized) > 1) {
                    $exportData[] = ["FollowTagSubscription/tag-{$tag->id}.json" => $this->encodeForExport($sanitized)];
                }
            });

        return $exportData;
    }

    public function sanitize(TagState $state): array
    {
        return Arr::except($state->toArray(), ['user_id', 'marked_as_read_at', 'is_hidden']);
    }

    public function anonymize(): void
    {
        Tag::query()
            ->each(function (Tag $tag) {
                $state = $tag->stateFor($this->user);

                if ($state->exists) {
                    $state->subscription = null;
                    $state->save();
                }
            });
    }

    public static function deleteDescription(): string
    {
        return self::anonymizeDescription();
    }

    public function delete(): void
    {
        $this->anonymize();
    }
}

<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags;

use Flarum\Api\Controller\ShowForumController;
use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Tags\Tag;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Psr\Http\Message\ServerRequestInterface;

class LoadPromptTags
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param ShowForumController    $controller
     * @param array                  $data
     * @param ServerRequestInterface $request
     */
    public function __invoke(ShowForumController $controller, &$data, ServerRequestInterface $request): void
    {
        $promptEnabled = $this->settings->get('fof-follow-tags.prompt_new_users') === '1';
        $buttonEnabled = $this->settings->get('fof-follow-tags.prompt_button_on_following_page') === '1';

        // The tag list is only needed when the modal can be opened, either
        // automatically or through the button on the Following page
        if (!$promptEnabled && !$buttonEnabled) {
            $data['fofFollowTagsPromptList'] = new Collection();

            return;
        }

        $actor = RequestUtil::getActor($request);

        $data['fofFollowTagsPromptList'] = $this->query($actor)->get();
    }

    protected function query(User $actor): Builder
    {
        /** @var Builder $query */
        $query = Tag::query()->whereVisibleTo($actor);

        $strategy = $this->settings->get('fof-follow-tags.prompt_tag_strategy') ?: 'primary';

        switch ($strategy) {
            case 'primary':
                return $query->where(function (Builder $query) {
                    $query
                        ->whereNull('parent_id')
                        ->whereNotNull('position');
                });
            case 'primaryAndChildren':
                return $query->where(function (Builder $query) {
                    $query
                        ->whereNotNull('parent_id')
                        ->orWhereNotNull('position');
                });
            case 'primaryAndSecondary':
                return $query->whereNull('parent_id');
            case 'secondary':
                return $query->whereNull('position');
            case 'list':
                $ids = json_decode($this->settings->get('fof-follow-tags.prompt_tag_ids', '[]'));

                return $query->whereIn('id', is_array($ids) ? $ids : []);
        }

        // case 'all'
        return $query;
    }
}

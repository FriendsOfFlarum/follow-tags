<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\Tests\integration\api;

use Flarum\Api\Context;
use Flarum\Api\Schema;
use Flarum\Extend;
use Flarum\Tags\Api\Resource\TagResource;
use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Extend\Subscription;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * Attributes other extensions allow, through the Subscription extender, to be
 * saved alongside a subscription by users who cannot edit the tag.
 */
class SubscriptionAttributesTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use ExtensionDepsTrait;
    use TagsDefinitionTrait;

    /** @var array<string, string|null> */
    public static array $saved = [];

    public function setUp(): void
    {
        parent::setUp();

        static::$saved = [];

        $this->extensionDeps();

        $this->prepareDatabase([
            User::class => [
                $this->normalUser(),
            ],
            Tag::class => $this->tags(),
        ]);

        $this->extend(
            (new Extend\ApiResource(TagResource::class))
                ->fields(fn () => [
                    Schema\Str::make('subscriptionNote')
                        ->nullable()
                        ->writable(fn (Tag $tag, Context $context) => $context->updating() && !$context->getActor()->isGuest())
                        ->set(function (Tag $tag, ?string $value) {
                            static::$saved['subscriptionNote'] = $value;
                        }),
                ])
        );
    }

    protected function allowSubscriptionNote(): void
    {
        $this->extend(
            (new Subscription())->attribute('subscriptionNote')
        );
    }

    /**
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $relationships
     */
    protected function patchTag(array $attributes, array $relationships = []): \Psr\Http\Message\ResponseInterface
    {
        $data = [
            'type'       => 'tags',
            'id'         => '1',
            'attributes' => $attributes,
        ];

        if ($relationships) {
            $data['relationships'] = $relationships;
        }

        return $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 2,
                'json'            => ['data' => $data],
            ])
        );
    }

    #[Test]
    public function regular_user_can_save_an_allowed_attribute_with_a_subscription()
    {
        $this->allowSubscriptionNote();

        $response = $this->patchTag(['subscription' => 'follow', 'subscriptionNote' => 'en']);

        $this->assertEquals(200, $response->getStatusCode(), (string) $response->getBody());
        $this->assertEquals('en', static::$saved['subscriptionNote'] ?? null);
        $this->assertEquals(
            'follow',
            $this->database()->table('tag_user')->where('user_id', 2)->where('tag_id', 1)->value('subscription')
        );
    }

    #[Test]
    public function regular_user_cannot_save_an_attribute_that_is_not_allowed()
    {
        $response = $this->patchTag(['subscription' => 'follow', 'subscriptionNote' => 'en']);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertArrayNotHasKey('subscriptionNote', static::$saved);
        $this->assertNull(
            $this->database()->table('tag_user')->where('user_id', 2)->where('tag_id', 1)->value('subscription')
        );
    }

    #[Test]
    public function regular_user_cannot_save_an_allowed_attribute_without_a_subscription()
    {
        $this->allowSubscriptionNote();

        $response = $this->patchTag(['subscriptionNote' => 'en']);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertArrayNotHasKey('subscriptionNote', static::$saved);
    }

    #[Test]
    public function regular_user_cannot_edit_tag_fields_alongside_allowed_attributes()
    {
        $this->allowSubscriptionNote();

        $response = $this->patchTag(['subscription' => 'follow', 'subscriptionNote' => 'en', 'name' => 'Hacked']);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertNotEquals('Hacked', $this->database()->table('tags')->where('id', 1)->value('name'));
    }

    #[Test]
    public function regular_user_cannot_send_relationships_alongside_allowed_attributes()
    {
        $this->allowSubscriptionNote();

        $response = $this->patchTag(
            ['subscription' => 'follow', 'subscriptionNote' => 'en'],
            ['parent' => ['data' => ['type' => 'tags', 'id' => '2']]]
        );

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertNull($this->database()->table('tags')->where('id', 1)->value('parent_id'));
    }
}

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

use Flarum\Extend;
use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Event\SubscriptionChanged;
use FoF\FollowTags\Event\SubscriptionChanging;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionEventsTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use ExtensionDepsTrait;
    use TagsDefinitionTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionDeps();

        $this->prepareDatabase([
            User::class => [
                $this->normalUser(),
            ],
            Tag::class => $this->tags(),
        ]);
    }

    protected function follow(): \Psr\Http\Message\ResponseInterface
    {
        return $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'subscription' => 'follow',
                        ],
                    ],
                ],
            ])
        );
    }

    #[Test]
    public function subscription_changing_is_dispatched_before_the_state_is_saved()
    {
        $events = [];
        $existedWhenDispatched = null;

        $this->extend(
            (new Extend\Event())
                ->listen(SubscriptionChanging::class, function (SubscriptionChanging $event) use (&$events, &$existedWhenDispatched) {
                    $events[] = $event;
                    $existedWhenDispatched = $event->state->exists;

                    // A listener can change the state before it is saved, as 1.x allowed.
                    $event->state->is_hidden = true;
                })
        );

        $response = $this->follow();

        $this->assertEquals(200, $response->getStatusCode(), (string) $response->getBody());
        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertEquals(2, $event->actor->id);
        $this->assertEquals(1, $event->tag->id);
        $this->assertEquals('follow', $event->state->subscription);
        $this->assertFalse($existedWhenDispatched);
        $this->assertEquals('follow', $event->request->getParsedBody()['data']['attributes']['subscription']);

        $this->assertTrue((bool) $this->database()->table('tag_user')->where('user_id', 2)->where('tag_id', 1)->value('is_hidden'));
    }

    #[Test]
    public function subscription_changed_is_dispatched_after_the_state_is_saved()
    {
        $events = [];

        $this->extend(
            (new Extend\Event())
                ->listen(SubscriptionChanged::class, function (SubscriptionChanged $event) use (&$events) {
                    $events[] = $event;
                })
        );

        $response = $this->follow();

        $this->assertEquals(200, $response->getStatusCode(), (string) $response->getBody());
        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertEquals(2, $event->actor->id);
        $this->assertEquals(1, $event->tag->id);
        $this->assertEquals('follow', $event->state->subscription);
        $this->assertTrue($event->state->exists);
    }
}

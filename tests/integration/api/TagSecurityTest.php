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

use Flarum\Group\Group;
use Flarum\Tags\Tag;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use FoF\FollowTags\Tests\integration\ExtensionDepsTrait;
use FoF\FollowTags\Tests\integration\TagsDefinitionTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * Tag Security Tests
 *
 * IMPORTANT: Why we test tag editing behavior in this extension
 * ============================================================
 *
 * This extension modifies Flarum's core tag authorization behavior to allow users
 * to subscribe to restricted tags. To enable this, we:
 *
 * 1. Use FORCE_ALLOW in TagPolicy->edit() to override the core TagPolicy's DENY
 *    for restricted tags when users have viewForum permission.
 *
 * 2. Override core tag field definitions in extend.php to require admin permission
 *    for editing name, slug, description, color, icon, isHidden, and isPrimary.
 *
 * These changes could potentially create security vulnerabilities if not properly
 * implemented. These tests ensure that:
 *
 * - Users can ONLY edit the subscription field, not tag metadata
 * - The FORCE_ALLOW doesn't accidentally grant unauthorized tag editing access
 * - Restricted tags remain properly protected
 * - Field-level permissions are correctly enforced
 *
 * Without these tests, a bug in our policy or field overrides could allow regular
 * users to modify tag names, colors, slugs, etc., which would be a critical
 * security issue.
 */
class TagSecurityTest extends TestCase
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

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_name_with_only_viewForum_permission()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'name' => 'Hacked Name',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the name field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());

        // Verify the name was not changed in the database
        $this->assertEquals(
            'Restricted',
            $this->database()->table('tags')
                ->where('id', 7)
                ->value('name')
        );
    }

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_slug_with_only_viewForum_permission()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'slug' => 'hacked-slug',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the slug field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());

        // Verify the slug was not changed in the database
        $this->assertEquals(
            'restricted',
            $this->database()->table('tags')
                ->where('id', 7)
                ->value('slug')
        );
    }

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_color_with_only_viewForum_permission()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'color' => '#FF0000',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the color field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());
    }

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_description_with_only_viewForum_permission()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'description' => 'Hacked description',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the description field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());
    }

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_icon_with_only_viewForum_permission()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'icon' => 'fas fa-bomb',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the icon field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());
    }

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_isHidden_with_only_viewForum_permission()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'isHidden' => true,
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the isHidden field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());
    }

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_isPrimary_with_only_viewForum_permission()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'isPrimary' => true,
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the isPrimary field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());
    }

    #[Test]
    public function regular_user_cannot_edit_unrestricted_tag_name()
    {
        $response = $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'name' => 'Hacked Name',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the name field is not writable for regular users
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());

        // Verify the name was not changed in the database
        $this->assertEquals(
            'General',
            $this->database()->table('tags')
                ->where('id', 1)
                ->value('name')
        );
    }

    #[Test]
    public function regular_user_cannot_edit_unrestricted_tag_slug()
    {
        $response = $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'slug' => 'hacked',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because the slug field is not writable for regular users
        $this->assertEquals(403, $response->getStatusCode());

        // Verify the slug was not changed in the database
        $this->assertEquals(
            'general',
            $this->database()->table('tags')
                ->where('id', 1)
                ->value('slug')
        );
    }

    #[Test]
    public function regular_user_cannot_view_restricted_tag_without_viewForum_permission()
    {
        // Do NOT grant viewForum permission for the restricted tag
        // User should not be able to even see that the tag exists

        $response = $this->send(
            $this->request('GET', '/api/tags/7', [
                'authenticatedAs' => 2,
            ])
        );

        // Should fail with 404 because the tag won't be found due to visibility scoping
        // This is critical for preventing information disclosure about restricted tags
        $this->assertEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function regular_user_cannot_subscribe_to_restricted_tag_without_viewForum_permission()
    {
        // Do NOT grant viewForum permission for the restricted tag
        // User should not be able to subscribe to a tag they cannot view

        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'subscription' => 'follow',
                        ],
                    ],
                ],
            ])
        );

        // Should fail with 404 because the tag won't be found due to visibility scoping
        // This prevents users from discovering the existence of restricted tags
        $this->assertEquals(404, $response->getStatusCode());

        // Verify no subscription was created
        $this->assertNull(
            $this->database()->table('tag_user')
                ->where('user_id', 2)
                ->where('tag_id', 7)
                ->value('subscription')
        );
    }

    #[Test]
    public function regular_user_can_view_restricted_tag_with_viewForum_permission()
    {
        // Grant viewForum permission for the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        $response = $this->send(
            $this->request('GET', '/api/tags/7', [
                'authenticatedAs' => 2,
            ])
        );

        // Should succeed - user can view the tag
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        // Verify we got the correct tag
        $this->assertEquals('7', $data['data']['id']);
        $this->assertEquals('Restricted', $data['data']['attributes']['name']);
    }

    #[Test]
    public function regular_user_cannot_edit_restricted_tag_without_viewForum_permission()
    {
        // Do NOT grant viewForum permission
        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'subscription' => 'follow',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because user doesn't have permission to view/access the tag
        // The tag won't even be found due to visibility scoping
        $this->assertEquals(404, $response->getStatusCode());

        // Verify no subscription was created
        $this->assertNull(
            $this->database()->table('tag_user')
                ->where('user_id', 2)
                ->where('tag_id', 7)
                ->value('subscription')
        );
    }

    #[Test]
    public function regular_user_can_only_update_subscription_on_restricted_tag_with_viewForum()
    {
        // Give the user permission to view the restricted tag
        $this->database()->table('group_permission')->insert([
            'group_id' => Group::MEMBER_ID,
            'permission' => 'tag7.viewForum',
        ]);

        // Try to update both subscription AND name in a single request
        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 2,
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'subscription' => 'follow',
                            'name'         => 'Hacked Name',
                        ],
                    ],
                ],
            ])
        );

        // Should fail because name field is not writable
        // 403 Forbidden is returned when trying to write to a field without permission
        $this->assertEquals(403, $response->getStatusCode());

        // Verify the name was not changed
        $this->assertEquals(
            'Restricted',
            $this->database()->table('tags')
                ->where('id', 7)
                ->value('name')
        );

        // Verify no subscription was created (transaction rolled back)
        $this->assertNull(
            $this->database()->table('tag_user')
                ->where('user_id', 2)
                ->where('tag_id', 7)
                ->value('subscription')
        );
    }

    #[Test]
    public function admin_can_still_edit_tag_fields()
    {
        // Admins should still be able to edit all tag fields
        $response = $this->send(
            $this->request('PATCH', '/api/tags/1', [
                'authenticatedAs' => 1, // Admin user
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '1',
                        'attributes' => [
                            'name' => 'Updated General',
                            'description' => 'Updated description',
                        ],
                    ],
                ],
            ])
        );

        // Admin should succeed
        $this->assertEquals(200, $response->getStatusCode());

        // Verify the changes were saved
        $this->assertEquals(
            'Updated General',
            $this->database()->table('tags')
                ->where('id', 1)
                ->value('name')
        );

        $this->assertEquals(
            'Updated description',
            $this->database()->table('tags')
                ->where('id', 1)
                ->value('description')
        );
    }

    #[Test]
    public function admin_can_edit_restricted_tag_fields()
    {
        // Admins should be able to edit restricted tags without needing specific permissions
        $response = $this->send(
            $this->request('PATCH', '/api/tags/7', [
                'authenticatedAs' => 1, // Admin user
                'json'            => [
                    'data' => [
                        'type'       => 'tags',
                        'id'         => '7',
                        'attributes' => [
                            'name' => 'Updated Restricted',
                            'color' => '#00FF00',
                        ],
                    ],
                ],
            ])
        );

        // Admin should succeed
        $this->assertEquals(200, $response->getStatusCode());

        // Verify the changes were saved
        $this->assertEquals(
            'Updated Restricted',
            $this->database()->table('tags')
                ->where('id', 7)
                ->value('name')
        );

        $this->assertEquals(
            '#00FF00',
            $this->database()->table('tags')
                ->where('id', 7)
                ->value('color')
        );
    }
}

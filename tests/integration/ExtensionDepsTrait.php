<?php

/*
 * This file is part of fof/follow-tags.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\FollowTags\tests\integration;

trait ExtensionDepsTrait
{
    public function extensionDeps(): void
    {
        $this->extension('flarum-tags');
        $this->extension('fof-extend');
        $this->extension('fof-follow-tags');
    }
}

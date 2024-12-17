<?php

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

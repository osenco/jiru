<?php

namespace Osen\Updater\Events;

use Osen\Updater\Models\Release;

class UpdateFailed
{
    protected $release;

    public function __construct(Release $release)
    {
        $this->release = $release;
    }
}

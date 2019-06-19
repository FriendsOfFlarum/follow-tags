<?php

use Flarum\Database\Migration;

return Migration::addColumns('tag_user', [
    'subscription' => ['enum', 'allowed' => ['follow', 'lurk', 'ignore'], 'nullable' => true]
]);
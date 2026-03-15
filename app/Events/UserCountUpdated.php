<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCountUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $onlineCount,
        public int $totalCount,
    ) {}
}
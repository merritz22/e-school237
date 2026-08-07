<?php

namespace App\Enums;

enum SubscriptionType: string
{
    case Classic = 'CLASSIC';
    case Premium = 'PREMIUM';
    case Advanced = 'ADVANCED';
}

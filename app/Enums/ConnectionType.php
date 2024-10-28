<?php

namespace App\Enums;

enum ConnectionType: string
{
    case NETWORK = 'network';
    case WINDOWS = 'windows';
    case LINUX = 'linux';
}
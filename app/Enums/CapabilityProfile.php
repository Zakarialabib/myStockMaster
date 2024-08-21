<?php

namespace App\Enums;

enum CapabilityProfile: string
{
    case DEFAULT = 'default';
    case SIMPLE = 'simple';
    case SP2000 = 'SP2000';
    case TEP_200M = 'TEP-200M';
    case P822D = 'P822D';
}
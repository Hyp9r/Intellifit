<?php

declare(strict_types=1);

namespace App\Api\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static ApiOperation CREATE()
 * @method static ApiOperation READ()
 * @method static ApiOperation INFO()
 * @method static ApiOperation UPDATE()
 * @method static ApiOperation LIST()
 * @method static ApiOperation SORT()

 * @extends Enum<string>
 */
class ApiOperation extends Enum
{
    // standard operations:
    private const CREATE = 'create';
    private const READ = 'read';
    private const INFO = 'info';
    private const UPDATE = 'update';

    // search operations:
    private const LIST = 'list';
    private const SORT = 'sort';
}

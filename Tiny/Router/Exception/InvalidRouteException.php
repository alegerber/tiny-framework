<?php

declare(strict_types=1);

namespace Tiny\Router\Exception;

use InvalidArgumentException;

class InvalidRouteException extends InvalidArgumentException
{
    public function __construct(string $route) {

        parent::__construct(sprintf('No config found for route %s', $route));
    }
}
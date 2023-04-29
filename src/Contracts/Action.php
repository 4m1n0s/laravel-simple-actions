<?php

namespace Am1n0s\LSA\Contracts;

use Closure;

interface Action
{
    public function handle(ActionPayload $payload, Closure $next = null): mixed;
}

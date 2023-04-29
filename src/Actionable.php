<?php

namespace Am1n0s\LSA;

use RuntimeException;
use Illuminate\Support\Fluent;
use Am1n0s\LSA\Contracts\Action;
use Am1n0s\LSA\Contracts\ActionPayload;
use Illuminate\Support\Facades\Pipeline;

abstract class Actionable
{
    protected static array $actions = [];

    public static function run(ActionPayload $payload): mixed
    {
        return count(static::$actions)
            ? Pipeline::send($payload)
                ->through(static::$actions)
                ->via('handle')
                ->thenReturn()
            : (
                new static instanceof Action
                    ? static::make()->handle($payload, fn ($payload) => $payload)
                    : throw new RuntimeException(static::class.' Must implements '.Action::class.' Interface.')
            );
    }

    private static function make(): Action
    {
        return app(static::class);
    }

    public static function runIf($boolean, ...$arguments): mixed
    {
        return $boolean ? static::run(...$arguments) : new Fluent;
    }

    public static function runUnless($boolean, ...$arguments): mixed
    {
        return static::runIf(! $boolean, ...$arguments);
    }
}

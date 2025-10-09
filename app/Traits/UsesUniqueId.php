<?php

namespace App\Traits;

use App\Services\UniqueIdGenerator;

trait UsesUniqueId
{
    protected function uniqueIdGenerator(): UniqueIdGenerator
    {
        // Laravel service container se resolve karega
        return app(UniqueIdGenerator::class);
    }
}

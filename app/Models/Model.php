<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
abstract class Model extends EloquentModel implements BuilderContract
{
}

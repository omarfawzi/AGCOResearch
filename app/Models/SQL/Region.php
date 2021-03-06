<?php

namespace App\Models\SQL;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use Filterable;

    protected $connection = 'sqlsrv';

    protected $table = 'Region';

    protected $primaryKey = 'RegionId';
}

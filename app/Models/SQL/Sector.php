<?php

namespace App\Models\SQL;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'GICS_Sector';

    protected $primaryKey = 'GICS_SectorId';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function getPrimaryKey()
    {
        return with(new static)->getKeyName();
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class,'subscribable',null,null,'GICS_SectorId');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->GICS_SectorId,
            'name' => $this->GICS_Sector
        ];
    }
}

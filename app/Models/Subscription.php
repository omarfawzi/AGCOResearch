<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public const SECTOR_SUBSCRIPTION_TYPE = 'sector';

    public const COMPANY_SUBSCRIPTION_TYPE = 'company';

    public const SUBSCRIPTION_TYPES = [
        self::SECTOR_SUBSCRIPTION_TYPE,
        self::COMPANY_SUBSCRIPTION_TYPE
    ];

    protected $connection = 'mysql';

    public function subscribable()
    {
        if ($this->getAttribute('subscribable_type') == self::SECTOR_SUBSCRIPTION_TYPE) {
            $this->setAttribute('subscribable_id', strval($this->getAttribute('subscribable_id')));
        }
        return $this->morphTo();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->subscribable_type,
            'subscribable' => optional($this->subscribable)->toArray()
        ];
    }

}

<?php

namespace App\Models\SQL;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'Company';

    protected $primaryKey = 'CompanyID';

    /**
     * @return string
     */
    public function getTicker(): string
    {
        return $this->Bloomberg;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'     => $this->CompanyID,
            'title'  => $this->Company,
            'ticker' => $this->getTicker(),
        ];
    }
}

<?php

namespace App\Schema;

use OpenApi\Annotations as OA;

/**
 * Class Sector
 * @OA\Schema(description="Sector Output Description")
 * @package App\Schema
 */
class Sector
{
    /**
     * @OA\Property(
     *     type="integer",
     *     description="ID"
     * )
     *
     * @var int $id
     */
    public $id;

    /**
     * @OA\Property(
     *     type="string",
     *     description= "Sector Name"
     * )
     *
     * @var string $name
     */
    public $name;
}

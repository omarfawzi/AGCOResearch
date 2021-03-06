<?php

namespace App\Schema\Output;

use OpenApi\Annotations as OA;

/**
 * Class Recommendation
 * @OA\Schema(description="Recommendation Output Description")
 *
 * @package App\Schema\Output
 */
class Recommendation
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
     *     description="Recommendation Name"
     * )
     *
     * @var string $name
     */
    public $name;
}

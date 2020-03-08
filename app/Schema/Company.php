<?php

namespace App\Schema;
use OpenApi\Annotations as OA;
/**
 * Class Company
 * @OA\Schema(description="Company Output Description")
 *
 * @package App\Schema
 */
class Company
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
     *     description="Company Title"
     * )
     *
     * @var string $title
     */
    public $title;
    /**
     * @OA\Property(
     *     type="string",
     *     description="Company Ticker"
     * )
     *
     * @var string $ticker
     */
    public $ticker;
}

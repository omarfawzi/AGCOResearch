<?php

namespace App\Schema\Output;
use OpenApi\Annotations as OA;

/**
 * Class ReportDetail
 * @OA\Schema(description="ReportDetail Output Description")
 * @package App\Schema\Output
 */
class ReportDetail extends Report
{
    /**
     * @OA\Property(
     *     type="string",
     *     description="Report Path"
     * )
     *
     * @var string|null $path
     */
    public $path;

    /**
     * @OA\Property(type="array",@OA\Items(type="object",ref="#/components/schemas/Sector"))
     *
     * @var Sector[] $sectors
     */
    public $sectors;

    /**
     * @OA\Property(type="array",@OA\Items(type="object",ref="#/components/schemas/Company"))
     *
     * @var Company[] $companies
     */
    public $companies;

    /**
     * @OA\Property(type="array",@OA\Items(type="object",ref="#/components/schemas/Recommendation"))
     *
     * @var Recommendation[] $recommendations
     */
    public $recommendations;

    /**
     * @OA\Property(
     *     type="string",
     *     description="Report Summary"
     * )
     * @var string $summary
     */
    public $summary;

}
<?php

namespace App\Http\Controllers;

use App\Factories\TransformerFactory;
use App\Repositories\CompanyRepository;
use App\Traits\FractalView;
use App\Transformers\CompanyTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CompanyController
{
    use FractalView;

    /** @var CompanyRepository $companyRepository */
    protected $companyRepository;

    /** @var TransformerFactory $transformerFactory */
    protected $transformerFactory;

    /**
     * CompanyController constructor.
     *
     * @param CompanyRepository  $companyRepository
     * @param TransformerFactory $transformerFactory
     */
    public function __construct(CompanyRepository $companyRepository, TransformerFactory $transformerFactory)
    {
        $this->companyRepository  = $companyRepository;
        $this->transformerFactory = $transformerFactory;
    }

    /**
     * @OA\Get(
     *     path="/companies",
     *     summary="Get Companies",
     *     tags={"Companies"},
     *     @OA\Parameter(in="query",name="pagination",@OA\Schema(ref="#/components/schemas/ListParams")),
     *     @OA\Parameter(in="query",name="name",required=false,@OA\Schema(type="string")),
     *     @OA\Response(
     *        response="200",
     *        description="Get Companies",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *            @OA\Property(property="data",type="array",@OA\Items(type="object",ref="#/components/schemas/Company")),
     *            @OA\Property(property="meta", ref="#/components/schemas/Meta")
     *        )
     *      )
     *    )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $companies = $this->companyRepository->getCompanies(
            $request->get('limit', config('api.defaults.limit')),
            $request->all()
        );

        return $this->listView($companies, $this->transformerFactory->make(CompanyTransformer::class));
    }

    public function getAllCompanies(Request $request): JsonResponse
    {
        $companies = $this->companyRepository->getAllCompanies();
        return $this->singleView($companies, $this->transformerFactory->make(CompanyTransformer::class));
    }
}

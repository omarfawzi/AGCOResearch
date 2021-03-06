<?php

namespace App\Http\Controllers;

use App\Factories\TransformerFactory;
use App\Repositories\RegionRepository;
use App\Traits\FractalView;
use App\Transformers\RegionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class RegionController
{
    use FractalView;

    /** @var TransformerFactory $transformerFactory */
    protected $transformerFactory;

    /** @var RegionRepository $regionRepository */
    protected $regionRepository;

    /**
     * RegionController constructor.
     *
     * @param TransformerFactory $transformerFactory
     * @param RegionRepository   $regionRepository
     */
    public function __construct(TransformerFactory $transformerFactory, RegionRepository $regionRepository)
    {
        $this->transformerFactory = $transformerFactory;
        $this->regionRepository   = $regionRepository;
    }

    /**
     * @OA\Get(
     *     path="/regions",
     *     summary="Get Regions",
     *     tags={"Regions"},
     *     @OA\Parameter(in="query",name="pagination",@OA\Schema(ref="#/components/schemas/ListParams")),
     *     @OA\Parameter(in="query",name="name",required=false,@OA\Schema(type="string")),
     *     @OA\Response(
     *        response="200",
     *        description="Get Regions",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *            @OA\Property(property="data",type="array",@OA\Items(type="object",ref="#/components/schemas/Region")),
     *            @OA\Property(property="meta", ref="#/components/schemas/Meta")
     *        )
     *      )
     *    )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $regions = $this->regionRepository->getRegions(
            $request->get('limit', config('api.defaults.limit')),
            $request->all()
        );

        return $this->listView($regions,$this->transformerFactory->make(RegionTransformer::class));
    }
}

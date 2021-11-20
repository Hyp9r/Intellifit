<?php

declare(strict_types=1);

namespace App\Controller;

use App\Api\Api;
use App\Api\Enum\ApiOperation;
use App\Api\Exception\ApiException;
use App\Entity\Food;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class FoodController
 * @package App\Controller
 * @Route(path="api/food", name="food.")
 * @SWG\Tag(name="Food")
 */
class FoodController extends AbstractController
{
    /**
     * @var FoodRepository $foodRepository
     */
    protected FoodRepository $foodRepository;

    /**
     * @var EntityManagerInterface $entityManager
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var Api $api
     */
    protected Api $api;


    public function __construct(
        FoodRepository $foodRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->foodRepository = $foodRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="find")
     * @SWG\Get(
     *     summary="Get Food",
     *     description="Find and return one specific Food entity by ID.",
     *     @SWG\Parameter(
     *         in="path",
     *         name="id",
     *         description="Identifier (ID) of the entity. Example: `3`"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returned specific entity of Food.",
     *         @Model(type=\App\Entity\Food::class, groups={"read"})
     *     ),
     *     @SWG\Response(response=404, description="Returned when no entity was found."),
     *     @SWG\Response(response=410, description="Returned when entity is no longer available.")
     * )
     */
    public function find(Food $food): Response
    {
        return $this->json($this->foodRepository->find($food));
    }

    /**
     * @Route(path="", methods={"POST"}, name="create")
     * @SWG\Post(
     *     summary="Create Food",
     *     description="Create and insert a new Food entity to the database.",
     *     @SWG\RequestBody(@Model(type=\App\Entity\Food::class, groups={"create"})),
     *     @SWG\Response(
     *         response=201,
     *         description="Returns newly created entity of Food.",
     *         @Model(type=\App\Entity\Food::class, groups={"read"})
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Returned when request contains invalid data.",
     *         @Model(type=\App\Model\Error::class)
     *     )
     * )
     * @throws ApiException
     * @throws ExceptionInterface
     */
    public function create(Request $request): Response
    {
        $data = $this->api->decodeRequestJson($request);
        $food = $this->api->denormalizeObject($data, Food::class, ApiOperation::CREATE());
        $this->entityManager->persist($food);
        $this->entityManager->flush();
    }

}
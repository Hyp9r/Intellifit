<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MealController
 * @package App\Controller
 * @Route(path="api/meal", name="meal.")
 * @SWG\Tag(name="Meal")
 */
class MealController extends AbstractController
{
    /**
     * @var MealRepository $mealRepository
     */
    protected MealRepository $mealRepository;

    /**
     * @var EntityManagerInterface $entityManager
     */
    protected EntityManagerInterface $entityManager;

    /**
     * MealController constructor.
     * @param MealRepository $mealRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MealRepository $mealRepository, EntityManagerInterface $entityManager)
    {
        $this->mealRepository = $mealRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/{id}", methods={"GET"}, name="find")
     * @SWG\Get(
     *     summary="Get Meal",
     *     description="Find and return one specific Meal entity by ID.",
     *     @SWG\Parameter(
     *         in="path",
     *         name="id",
     *         description="Identifier (ID) of the entity. Example: `3`"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returned specific entity of Food.",
     *         @Model(type=\App\Entity\Meal::class, groups={"read"})
     *     ),
     *     @SWG\Response(response=404, description="Returned when no entity was found."),
     *     @SWG\Response(response=410, description="Returned when entity is no longer available.")
     * )
     */
    public function find(Meal $meal): JsonResponse
    {
        return $this->json($this->mealRepository->find($meal));
    }
}
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route(path="api/user", name="user.")
 * @SWG\Tag(name="User")
 */
class UserController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="find", requirements={"id":"\d+"})
     * @SWG\Get(
     *     summary="Get User",
     *     description="Find and return one specific User entity by ID.",
     *     @SWG\Parameter(
     *         in="path",
     *         name="id",
     *         description="Identifier (ID) of the entity. Example: `3`",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returned specific entity of User.",
     *         @Model(type=\App\Entity\User::class, groups={"read"})
     *     ),
     *     @SWG\Response(response=404, description="Returned when no entity was found."),
     *     @SWG\Response(response=410, description="Returned when entity is no longer available.")
     * )
     */
    public function find(User $user): JsonResponse
    {
        return $this->json($user);
    }
}
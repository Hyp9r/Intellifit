<?php

declare(strict_types=1);

namespace App\Controller;

use App\Api\Api;
use App\Api\Exception\ApiException;
use App\Entity\User;
use App\Model\Security\UserLogin;
use App\Security\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class LoginController
 * @package App\Controller
 * @Route(path="api/login", name="login.")
 * @SWG\Tag(name="Login")
 */
class LoginController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;
    private TokenGenerator $tokenGenerator;
    private Api $api;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        TokenGenerator $tokenGenerator,
        Api $api
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->api = $api;
    }

    /**
     * @Route(path="/user", methods={"POST"}, name="user")
     * @SWG\Post(
     *     summary="Login User",
     *     description="Authenticate User and return object with JSON Web Token. It's possible to login via email or phone number.",
     *     @SWG\RequestBody(
     *          @Model(type=\App\Model\Security\UserLogin::class),
     *          description="JSON with login credencials in Request body."
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returned JSON Web Token",
     *         @Model(type=\App\Model\Security\Token::class)
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Returned when request contains invalid data.",
     *         @Model(type=\App\Model\Error::class)
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Returned when authentication failed due to incorrect login or password."
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Returned when the account has been deactivated."
     *     ),
     *     @SWG\Response(
     *         response=410,
     *         description="Returned when the account is no longer available."
     *     ),
     * )
     *
     * @throws ExceptionInterface|ApiException
     */
    public function user(Request $request): Response
    {
        dd($request);

        $data = $this->api->decodeRequestJson($request);
        $login = $this->api->denormalizeObject($data, UserLogin::class);
        assert($login instanceof UserLogin);

        $user = $this->entityManager->getRepository(User::class)->findOneByLogin($login);

        return $this->createAuthentication($user, $login->getPassword());
    }

    /**
     * Authenticates admin/user account and returns token for authorization.
     * If authentication fails, error status code is returned instead.
     */
    private function createAuthentication(?User $user, string $password): Response
    {
        if (null === $user || false === $this->passwordHasher->isPasswordValid($user, $password)) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        if ($user->isDeleted()) {
            return new Response('', Response::HTTP_GONE);
        }

        if (false === $user->isActive()) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $authentication = $this->tokenGenerator->generateAuthentication($user);

        return $this->json($authentication);
    }
}

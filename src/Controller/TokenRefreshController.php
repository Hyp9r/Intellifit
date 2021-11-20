<?php

namespace App\Controller;

use App\Api\Api;
use App\Api\Exception\ApiException;
use App\Model\Security\RefreshToken;
use App\Security\TokenGenerator;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @SWG\Tag(name="Refresh Token")
 */
class TokenRefreshController extends AbstractController
{
    private TokenGenerator $tokenGenerator;
    private Api $api;

    public function __construct(
        TokenGenerator $tokenGenerator,
        Api $api
    ) {
        $this->tokenGenerator = $tokenGenerator;
        $this->api = $api;
    }

    /**
     * @Route("refresh-token", methods={"POST"}, name="refresh_token")
     * @SWG\Post(
     *     summary="Refresh Token",
     *     description="Authenticate and return object with JSON Web Token.",
     *     @SWG\RequestBody(
     *          @Model(type=\App\Model\Security\RefreshToken::class),
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
     * )
     *
     * @throws ExceptionInterface|ApiException
     */
    public function refreshToken(Request $request): Response
    {
        $data = $this->api->decodeRequestJson($request);
        $refresh = $this->api->denormalizeObject($data, RefreshToken::class);
        assert($refresh instanceof RefreshToken);

        $token = $this->tokenGenerator->refresh($refresh->getRefreshToken());

        if (null === $token) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }
        return $this->json($token);
    }
}

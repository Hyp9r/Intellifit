<?php

declare(strict_types=1);

namespace App\Controller;

use App\Api\Swagger\ApiDocGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FoodController
 * @package App\Controller
 * @Route(path="/api", name="api.")
 */
class ApiController extends AbstractController
{
    /**
     * @var ApiDocGenerator
     */
    protected ApiDocGenerator $apiDocGenerator;

    public function __construct(ApiDocGenerator $apiDocGenerator)
    {
        $this->apiDocGenerator = $apiDocGenerator;
    }

    /**
     * @Route("/doc", methods={"GET"}, name="doc")
     */
    public function index(): Response
    {
        return $this->render('api_doc.html.twig', [
            'apiDoc' => $this->apiDocGenerator->generate()
        ]);
    }
}
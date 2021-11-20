<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\Enum\ApiOperation;
use App\Api\Exception\ApiException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Api
{
    /**
     * @var Serializer $serializer
     */
    private Serializer $serializer;

    /**
     * @var EntityManager $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * Api constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager= $entityManager;
    }


    /**
     * @throws ApiException
     */
    public function decodeRequestJson(Request $request, bool $allowEmpty = false): array
    {
        $content = $request->getContent();

        if (!is_string($content)) {
            throw new ApiException('Content is not type of string.');
        }

        return $this->decodeJson($content, $allowEmpty);
    }

    /**
     * @throws ApiException
     */
    public function decodeJson(string $content, bool $allowEmpty): array
    {
        if (empty($content)) {
            if ($allowEmpty) {
                return [];
            }

            throw new ApiException('Missing JSON data.');
        }

        try {
            $data = (array) $this->serializer->decode($content, 'json');
        } catch (NotEncodableValueException $e) {
            throw new ApiException('Invalid JSON data.');
        }

        return $data;
    }

    /**
     * @param object|null $object object to populate
     *
     * @throws ApiException
     * @throws ExceptionInterface
     */
    public function denormalizeObject(
        array $data,
        string $class,
        ?ApiOperation $operation = null,
        ?object $object = null,
        ?bool $overrideAllowExtraAttributes = null
    ): object {
        $context = [
            ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ObjectNormalizer::ALLOW_EXTRA_ATTRIBUTES => $overrideAllowExtraAttributes,
        ];

        if (null !== $operation) {
            $context[ObjectNormalizer::GROUPS] = [$operation->getValue()];
        }

        if (null !== $object) {
            $context[ObjectNormalizer::OBJECT_TO_POPULATE] = $object;
        }

        return $this->denormalize($data, $class, null, $context);
    }

    /**
     * @param mixed       $data    Data to restore
     * @param string      $class   The expected class to instantiate
     * @param string|null $format  Format the given data was extracted from
     * @param array       $context Options available to the denormalizer
     *
     * @throws ApiException
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $class, ?string $format, array $context): object
    {
        try {
            return (object) $this->serializer->denormalize($data, $class, $format, $context);
            /* @phpstan-ignore-next-line */
        } catch (ExtraAttributesException $extraAttributesException) {
            throw ApiException::fromExtraAttributesException($extraAttributesException);
        } catch (NotNormalizableValueException $notNormalizableValueException) {
            throw ApiException::fromNotNormalizableValueException($notNormalizableValueException);
        }
    }

//    /**
//     * @param object|array $model
//     */
//    public function getResultResponse($model, array $groups = null, array $context = []): Response
//    {
//        $json = $this->serializeToJson($model);
//
//        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
//    }
//
//    /**
//     * @param object|array $model
//     */
//    public function serializeToJson($model): string
//    {
//        return $this->serializer->serialize($model, 'json');
//    }
}
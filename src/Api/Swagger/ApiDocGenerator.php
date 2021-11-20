<?php

declare(strict_types=1);

namespace App\Api\Swagger;

use OpenApi\Annotations\OpenApi;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiDocGenerator
{
    private ServiceLocator $generatorLocator;

    public function __construct(ServiceLocator $generatorLocator)
    {
        $this->generatorLocator = $generatorLocator;
    }

    public function generate(): OpenApi
    {
        return $this->generatorLocator->get('default')->generate();
    }

    public function removeFields(array &$spec, array $toRemove): void
    {
        foreach ($spec as $key => &$data) {
            if (is_string($data)) {
                foreach ($toRemove as [$attribute, $value]) {
                    if ($key === $attribute && $data === $value) {
                        unset($spec[$key]);
                        continue;
                    }
                }
            }

            if (is_array($data)) {
                $this->removeFields($data, $toRemove);
            }
        }
    }
}

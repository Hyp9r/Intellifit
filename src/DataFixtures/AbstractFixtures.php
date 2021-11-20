<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use ReflectionClass;
use RuntimeException;

abstract class AbstractFixtures extends Fixture
{
    private static array $entityReferenceNames = [];

    protected function addEntity(object $entity, string $id): void
    {
        $referenceName = $this->generateReferenceName(get_class($entity), $id);
        self::$entityReferenceNames[get_class($entity)][] = $referenceName;

        $this->setReference($referenceName, $entity);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return array<int, T>
     */
    protected function getEntityReferences(string $entityClass): array
    {
        $names = self::$entityReferenceNames[$entityClass] ?? [];

        $entities = [];
        foreach ($names as $name) {
            /** @var T $reference */
            $reference = $this->getReference($name);
            $entities[] = $reference;
        }

        return $entities;
    }

    protected function generateReferenceName(string $entityClass, string $uniqueId): string
    {
        return implode('--', [$entityClass, $uniqueId]);
    }

    protected function patchId(object $object, string $id): void
    {
        $this->patchProperty($object, 'id', $id);
    }

    /**
     * @param mixed $value
     */
    private function patchProperty(object $object, string $property, $value): void
    {
        $reflection = new ReflectionClass($object);

        do {
            if ($reflection->hasProperty($property)) {
                $reflectionProperty = $reflection->getProperty($property);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($object, $value);

                return;
            }

            $reflection = $reflection->getParentClass();
        } while (false !== $reflection);

        throw new RuntimeException(sprintf("Error while patching '%s' on class %s", $property, get_class($object)));
    }
}

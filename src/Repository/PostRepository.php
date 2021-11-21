<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Food>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param string $name
     * @return Post[]|null
     */
    public function findByName(string $name): ?Post
    {
        return $this->findBy(['name' => $name]);
    }
}
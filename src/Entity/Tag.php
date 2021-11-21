<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "list"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @SWG\Property(type="string", description="Title of the tag.", example="Workout")
     *
     * @Groups({"read", "list", "create", "update"})
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @SWG\Property(type="string", description="Description of the tag.", example="This tag marks that post has exercises details.")
     *
     * @Groups({"read", "list", "create", "update"})
     */
    private string $description;

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     * @var Collection<int, Post>
     */
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
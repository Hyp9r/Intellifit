<?php

declare(strict_types=1);

namespace App\Entity;

use Cake\Chronos\Chronos;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\Table(name="post")
 */
class Post
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
     * @SWG\Property(type="string", description="Title of the post.", example="PHP 8.0. changes")
     *
     * @Groups({"read", "list", "create", "update"})
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @SWG\Property(type="string", description="Body of the post.", example="Alot of text + images(links to images).")
     *
     * @Groups({"read", "list", "create", "update"})
     */
    private string $body;

    /**
     * @ORM\Column(type="chronos_datetime")
     * @SWG\Property(example="2020-05-25T13:36:05+00:00")
     *
     * @Groups({"read", "list", "sort"})
     */
    private Chronos $datePublished;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @SWG\Property(type="string", description="Slug of the post.", example="PHP-8.0.-changes")
     *
     * @Groups({"read", "list", "create", "update"})
     */
    private string $slug;

    /**
     * @ORM\Column(type="integer")
     *
     * @SWG\Property(type="integer", description="View counter of the post.", example="10 000")
     *
     * @Groups({"read", "list"})
     */
    private int $counter;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     * @ORM\JoinTable(name="post_tag")
     *
     * @Groups({"read", "list", "create", "update"})
     *
     * @var Collection<int,Tag>
     */
    private Collection $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"read", "list", "create"})
     */
    private User $user;

    /**
     * @ORM\Column(type="boolean")
     *
     * @SWG\Property(type="boolean", description="Flag which sets if post is visable to other people.", example="true")
     *
     * @Groups({"read", "list", "create", "update"})
     */
    private bool $visible;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     * @Groups({"create", "read", "list"})
     * @var Collection<int,Comment>
     */
    private Collection $comments;

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
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return Chronos
     */
    public function getDatePublished(): Chronos
    {
        return $this->datePublished;
    }

    /**
     * @param Chronos $datePublished
     */
    public function setDatePublished(Chronos $datePublished): void
    {
        $this->datePublished = $datePublished;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     */
    public function setCounter(int $counter): void
    {
        $this->counter = $counter;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     */
    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Collection $comments
     */
    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }
}
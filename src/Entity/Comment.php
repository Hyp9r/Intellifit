<?php

declare(strict_types=1);

namespace App\Entity;

use Cake\Chronos\Chronos;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="comment")
 */
class Comment
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
     * @SWG\Property(type="string", description="Text of the comment.", example="This is a comment")
     *
     * @Groups({"read", "list"})
     */
    private string $body;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @SWG\Property(type="integer", description="Number of up votes.", example="999")
     *
     * @Groups({"read", "list"})
     */
    private string $upVotes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @SWG\Property(type="integer", description="Number of down votes.", example="999")
     *
     * @Groups({"read", "list"})
     */
    private string $downVotes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private Post $post;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private Chronos $datePublished;

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
     * @return string
     */
    public function getUpVotes(): string
    {
        return $this->upVotes;
    }

    /**
     * @param string $upVotes
     */
    public function setUpVotes(string $upVotes): void
    {
        $this->upVotes = $upVotes;
    }

    /**
     * @return string
     */
    public function getDownVotes(): string
    {
        return $this->downVotes;
    }

    /**
     * @param string $downVotes
     */
    public function setDownVotes(string $downVotes): void
    {
        $this->downVotes = $downVotes;
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
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
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
}
<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="posts")
     */
    private $categories;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity=File::class, inversedBy="post", cascade={"persist", "remove"})
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $admin;
//
//    /**
//     * @ORM\Column(type="integer", options={"default" : 0})
//     */
//    private $sum_of_grades;
//
//    /**
//     * @ORM\Column(type="integer", options={"default" : 0})
//     */
//    private $number_of_grades;

    /**
     * @ORM\Column(type="boolean")
     */
    private $main_page;

    /**
     * @ORM\Column(type="boolean")
     */
    private $only_for_registered;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorites")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="post", orphanRemoval=true)
     */
    private $ratings;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }
    /**
     * @return Collection|Category[]
     */
    public function getCategories(): ?Collection
    {
        return $this->categories ?? null;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addPost($this);
        }

        return $this;
    }

    public function removeCategory(Category $category)//: self
    {
        if (!$this->categories->contains($category)) {
            return;
        }

        $this->categories->removeElement($category);
        $category->removePost($this);
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
    * @ORM\PrePersist
    */
    public function prePersist()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
        $this->number_of_grades = 0;
        $this->sum_of_grades = 0;
    }
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated_at = new \DateTime("now");
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

//    public function getSumOfGrades(): ?int
//    {
//        return $this->sum_of_grades;
//    }
//
//    public function setSumOfGrades(int $sum_of_grades): self
//    {
//        $this->sum_of_grades = $sum_of_grades;
//
//        return $this;
//    }
//
//    public function getNumberOfGrades(): ?int
//    {
//        return $this->number_of_grades;
//    }
//
//    public function setNumberOfGrades(int $number_of_grades): self
//    {
//        $this->number_of_grades = $number_of_grades;
//
//        return $this;
//    }

    public function getMainPage(): ?bool
    {
        return $this->main_page;
    }

    public function setMainPage(bool $main_page): self
    {
        $this->main_page = $main_page;

        return $this;
    }

    public function getOnlyForRegistered(): ?bool
    {
        return $this->only_for_registered;
    }

    public function setOnlyForRegistered(bool $only_for_registered): self
    {
        $this->only_for_registered = $only_for_registered;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user)//: self
    {
        if ($this->users->contains($user)) {
        $this->users->removeElement($user);
        $user->removeFavorite($this);
    }
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setPost($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getPost() === $this) {
                $rating->setPost(null);
            }
        }

        return $this;
    }
}

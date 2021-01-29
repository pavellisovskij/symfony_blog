<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
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
    private $brand_name;

    /**
     * @ORM\OneToOne(targetEntity=File::class, cascade={"persist", "remove"})
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $postComment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $premoderation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $anonComments;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): self
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    public function getLogo(): ?File
    {
        return $this->logo;
    }

    public function setLogo(?File $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPostComment(): ?bool
    {
        return $this->postComment;
    }

    public function setPostComment(bool $postComment): self
    {
        $this->postComment = $postComment;

        return $this;
    }

    public function getPremoderation(): ?bool
    {
        return $this->premoderation;
    }

    public function setPremoderation(bool $premoderation): self
    {
        $this->premoderation = $premoderation;

        return $this;
    }

    public function getAnonComments(): ?bool
    {
        return $this->anonComments;
    }

    public function setAnonComments(bool $anonComments): self
    {
        $this->anonComments = $anonComments;

        return $this;
    }
}

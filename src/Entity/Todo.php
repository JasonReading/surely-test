<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodoRepository")
 */
class Todo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list","detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list","detail"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"list","detail"})
     */
    private $completed;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"list","detail"})
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Groups({"list","detail"})
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private $dueDate;

    public function __construct()
    {
        $this->completed = false;
        $this->createdDate = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate = null): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }
}

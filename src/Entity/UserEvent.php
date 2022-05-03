<?php

namespace App\Entity;

use App\Repository\UserEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEventRepository::class)]
class UserEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private $userRef;

    #[ORM\Column(type: 'string', length: 255)]
    private $Event;

    #[ORM\Column(type: 'datetime')]
    private $timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRef(): ?User
    {
        return $this->userRef;
    }

    public function setUserRef(?User $userRef): self
    {
        $this->userRef = $userRef;

        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->Event;
    }

    public function setEvent(string $Event): self
    {
        $this->Event = $Event;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}

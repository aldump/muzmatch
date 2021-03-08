<?php

namespace App\Entity;

use App\Repository\SwipeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SwipeRepository::class)
 */
class Swipe
{
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="swipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="targetSwipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $target;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $status = false;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTarget(): User
    {
        return $this->target;
    }

    public function setTarget(User $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}

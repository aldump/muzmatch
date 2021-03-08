<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @OA\Property(type="integer")
     * @Groups({"user_read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @OA\Property(type="string", maxLength=50)
     * @Groups({"user_read"})
     */
    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Length(max: 50)]
    private string $username;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @OA\Property(type="string", maxLength=255)
     * @Groups({"user_read"})
     */
    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Length(max: 255)]
    #[Assert\Email(mode: 'strict')]
    private string $email;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @OA\Property(type="string")
     */
    #[Assert\NotBlank(normalizer: 'trim')]
    private ?string $plainPassword = null;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"user_read"})
     */
    #[Assert\Type(type: 'int')]
    private int $gender;

    /**
     * @ORM\Column(type="smallint", options={"unsigned": true})
     * @Groups({"user_read"})
     */
    #[Assert\Type(type: 'int')]
    private int $age;

    /**
     * @ORM\OneToMany(targetEntity=Swipe::class, mappedBy="user")
     */
    private Collection $swipes;

    /**
     * @ORM\OneToMany(targetEntity=Swipe::class, mappedBy="target")
     */
    private Collection $targetSwipes;

    public function __construct()
    {
        $this->swipes = new ArrayCollection();
        $this->targetSwipes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        //throw new BadMethodCallException('Method is not allowed');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        //throw new BadMethodCallException('Method is not allowed');
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return [static::ROLE_USER];
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return Collection|Swipe[]
     */
    public function getSwipes(): Collection
    {
        return $this->swipes;
    }

    public function addTarget(Swipe $target): self
    {
        if (!$this->swipes->contains($target)) {
            $this->swipes[] = $target;
            $target->setUser($this);
        }

        return $this;
    }

    public function removeTarget(Swipe $target): self
    {
        if ($this->swipes->removeElement($target)) {
            // set the owning side to null (unless already changed)
            if ($target->getUser() === $this) {
                $target->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Swipe[]
     */
    public function getTargetSwipes(): Collection
    {
        return $this->targetSwipes;
    }

    public function addTargetSwipe(Swipe $targetSwipe): self
    {
        if (!$this->targetSwipes->contains($targetSwipe)) {
            $this->targetSwipes[] = $targetSwipe;
            $targetSwipe->setTarget($this);
        }

        return $this;
    }

    public function removeTargetSwipe(Swipe $targetSwipe): self
    {
        if ($this->targetSwipes->removeElement($targetSwipe)) {
            // set the owning side to null (unless already changed)
            if ($targetSwipe->getTarget() === $this) {
                $targetSwipe->setTarget(null);
            }
        }

        return $this;
    }
}

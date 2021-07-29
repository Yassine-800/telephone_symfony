<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *          fields={"username"},
 *          message="nom de user deja pris"
 * )
 */
class User implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     *
     * @Assert\EqualTo(propertyPath="password")
     *
     */
    private $passwordConfirm;

    /**
     * @ORM\OneToMany(targetEntity=Telephone::class, mappedBy="user", orphanRemoval=true)
     */
    private $createur;

    public function __construct()
    {
        $this->createur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getSalt(){
    }

    public function getRoles(): array
    {
        return array('ROLE_USER');
}
    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return Collection|telephone[]
     */
    public function getCreateur(): Collection
    {
        return $this->createur;
    }

    public function addCreateur(telephone $createur): self
    {
        if (!$this->createur->contains($createur)) {
            $this->createur[] = $createur;
            $createur->setUser($this);
        }

        return $this;
    }

    public function removeCreateur(telephone $createur): self
    {
        if ($this->createur->removeElement($createur)) {
            // set the owning side to null (unless already changed)
            if ($createur->getUser() === $this) {
                $createur->setUser(null);
            }
        }

        return $this;
    }
}


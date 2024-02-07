<?php

namespace App\Entity;

use App\Repository\MyCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MyCollectionRepository::class)]
class MyCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 2083)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\ManyToOne(inversedBy: 'mycollections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'myfavoritescollections')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: MyObject::class, inversedBy: 'myCollections')]
    private Collection $myobjects;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->myobjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addMyFavoriteCollection($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeMyFavoriteCollection($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, MyObject>
     */
    public function getMyobjects(): Collection
    {
        return $this->myobjects;
    }

    public function addMyobject(MyObject $myobject): static
    {
        if (!$this->myobjects->contains($myobject)) {
            $this->myobjects->add($myobject);
        }

        return $this;
    }

    public function removeMyobject(MyObject $myobject): static
    {
        $this->myobjects->removeElement($myobject);

        return $this;
    }
}

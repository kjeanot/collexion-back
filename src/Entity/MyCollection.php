<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MyCollectionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MyCollectionRepository::class)]
class MyCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Groups(['get_collections', 'collection','get_object','get_user','get_collection','get_favorite'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 40)]
    #[Groups(['get_collections','collection','get_object','get_user','get_collection','get_favorite'])]
    private ?string $name = null;

    #[ORM\Column(length: 2083)]
    #[Assert\NotBlank,Assert\NotNull,Assert\Image]
    #[Groups(['get_collections','collection','get_object','get_user','get_collection','get_favorite'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank,Assert\NotNull,Assert\Length(min: 30, max: 2000),Assert\Type('string')]
    #[Groups(['get_collections','collection','get_object','get_collection','get_favorite'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true,type: Types::DECIMAL, precision: 2, scale: 1)]
    #[Groups(['get_collections','collection','get_object','get_collection','get_favorite'])]
    private ?string $rating = null;

    #[ORM\ManyToOne(inversedBy: 'mycollections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_collections','get_collection'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'myfavoritescollections')]
    #[Groups(['get_object'])]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: MyObject::class, inversedBy: 'myCollections')]
    #[Groups(['get_collections','get_collection',])]
    private Collection $myobjects;

    #[ORM\Column]
    #[Groups(['get_collections','get_favorite',])]
    #[Assert\Type('datetimeImmutable')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('datetimeImmutable')]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    #[Assert\NotBlank,Assert\NotNull,Assert\Type('bool')]
    private ?bool $is_active = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->myobjects = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->is_active = false;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }
}

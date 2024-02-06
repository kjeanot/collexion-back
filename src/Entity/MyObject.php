<?php

namespace App\Entity;

use App\Repository\MyObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MyObjectRepository::class)]
class MyObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 2083)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $condition = null;

    #[ORM\ManyToMany(targetEntity: MyCollection::class, mappedBy: 'myobjects')]
    private Collection $myCollections;

    public function __construct()
    {
        $this->myCollections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $Name): static
    {
        $this->name = $Name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getEtat(): ?string
    {
        return $this->condition;
    }

    public function setEtat(string $etat): static
    {
        $this->condition = $etat;

        return $this;
    }

    /**
     * @return Collection<int, MyCollection>
     */
    public function getMyCollections(): Collection
    {
        return $this->myCollections;
    }

    public function addMyCollection(MyCollection $myCollection): static
    {
        if (!$this->myCollections->contains($myCollection)) {
            $this->myCollections->add($myCollection);
            $myCollection->addMyobject($this);
        }

        return $this;
    }

    public function removeMyCollection(MyCollection $myCollection): static
    {
        if ($this->myCollections->removeElement($myCollection)) {
            $myCollection->removeMyobject($this);
        }

        return $this;
    }
}

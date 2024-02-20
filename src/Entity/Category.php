<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_categories','get_object', 'get_categorie_childs'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_categories','get_object', 'get_categorie_childs'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: MyObject::class, orphanRemoval: true)]
    #[Groups(['get_categorie_childs'])]
    private Collection $objects;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'categories')]
    #[Groups(['get_categorie_childs'])]
    private Collection $category;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'category')]
    private Collection $categories;

    #[ORM\Column(length: 2083)]
    #[Groups(['get_categories', 'get_categorie_childs'])]
    private ?string $image = null;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    /**
     * @return Collection<int, MyObject>
     */
    public function getObjects(): Collection
    {
        return $this->objects;
    }

    public function addObject(MyObject $object): static
    {
        if (!$this->objects->contains($object)) {
            $this->objects->add($object);
            $object->setCategory($this);
        }

        return $this;
    }

    public function removeObject(MyObject $object): static
    {
        if ($this->objects->removeElement($object)) {
            // set the owning side to null (unless already changed)
            if ($object->getCategory() === $this) {
                $object->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(self $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(self $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
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
}

<?php

namespace App\Entity;

use App\Repository\MyObjectRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MyObjectRepository::class)]
class MyObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_objects','get_collections','object','get_object','get_collection','get_categorie_childs'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_objects','get_collections','object','get_object','get_collection','get_categorie_childs'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_objects','object','get_object'])]
    private ?string $title = null;

    #[ORM\Column(length: 2083)]
    #[Groups(['get_objects','get_collections','object','get_object','get_collection','get_categorie_childs'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['get_objects','object','get_object','get_categorie_childs'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_objects','object','get_object','get_categorie_childs'])]
    private ?string $state = null;

    #[ORM\ManyToMany(targetEntity: MyCollection::class, mappedBy: 'myobjects', cascade: ['persist'])]
    #[Groups(['get_object'])]
    private Collection $myCollections;

    #[ORM\OneToMany(mappedBy: 'myObject', targetEntity: Comment::class, orphanRemoval: true)]
    #[Groups(['get_object'])]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'objects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_object'])]
    private ?Category $category = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->myCollections = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
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

    public function getstate(): ?string
    {
        return $this->state;
    }

    public function setstate(string $state): static
    {
        $this->state = $state;

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

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setMyObject($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMyObject() === $this) {
                $comment->setMyObject(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
}

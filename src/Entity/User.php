<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Groups(['get_collections', 'get_users','get_user','get_collection','get_page_object'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank,Assert\NotNull,Assert\Email]
    #[Groups(['get_users','get_user','get_collection_random'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['get_users','get_user'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank,Assert\NotNull]
    #[Groups(['get_user'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank,Assert\NotNull,Assert\Type('string'),Assert\Length(min: 3, max: 20)]
    #[Groups(['get_collections', 'get_users','get_user','get_collection','get_collection_random','get_page_object'])]
    private ?string $nickname = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 10, max: 1000),Assert\Type('string')]
    #[Groups(['get_users','get_user'])]
    private ?string $description = null;


    #[ORM\Column(length: 2083,nullable: true)]
    #[Assert\Image]
    #[Groups(['get_objects','get_collections','object','get_collection_random','get_page_object'])]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MyCollection::class, orphanRemoval: true)]
    #[Groups(['get_user'])]
    private Collection $mycollections;

    #[ORM\ManyToMany(targetEntity: MyCollection::class, inversedBy: 'users')]
    #[Groups(['get_user'])]
    private Collection $myfavoritescollections;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->mycollections = new ArrayCollection();
        $this->myfavoritescollections = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, MyCollection>
     */
    public function getMycollections(): Collection
    {
        return $this->mycollections;
    }

    public function addMycollection(MyCollection $mycollection): static
    {
        if (!$this->mycollections->contains($mycollection)) {
            $this->mycollections->add($mycollection);
            $mycollection->setUser($this);
        }

        return $this;
    }

    public function removeMycollection(MyCollection $mycollection): static
    {
        if ($this->mycollections->removeElement($mycollection)) {
            // set the owning side to null (unless already changed)
            if ($mycollection->getUser() === $this) {
                $mycollection->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MyCollection>
     */
    public function getMyfavoritescollections(): Collection
    {
        return $this->myfavoritescollections;
    }

    public function addMyFavoriteCollection(MyCollection $myfavoritescollection): static
    {
        if (!$this->myfavoritescollections->contains($myfavoritescollection)) {
            $this->myfavoritescollections->add($myfavoritescollection);
        }

        return $this;
    }

    public function removeMyFavoriteCollection(MyCollection $myfavoritescollection): static
    {
        $this->myfavoritescollections->removeElement($myfavoritescollection);

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}

<?php

    namespace App\Entity;

    use App\Repository\CommentRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
     * @UniqueEntity(
     *     fields={"email"},
     *     message="Ten email jest zajęty!"
     * )
     */
    class User implements UserInterface
    {
        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=180, unique=true)
         * @Assert\NotBlank(message="Wprowadź swój adres email")
         * @Assert\Email(message="Adres mail jest niepoprawny")
         * @Assert\NotNull(message="Wprowadź swój adres email")
         */
        private $email;

        /**
         * @ORM\Column(type="json")
         */
        private $roles = [];

        /**
         * @var string The hashed password
         * @ORM\Column(type="string")
         */
        private $password;

        /**
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        private $firstName;

        /**
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        private $lastName;

        /**
         * @ORM\Column(type="datetime")
         */
        private $agreedTermsAt;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", fetch="EXTRA_LAZY")
         */
        private $comments;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="author")
         */
        private $articles;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\UserPhoto", inversedBy="user", fetch="EAGER")
         */
        private $photo;


        public function __construct()
        {
            $this->comments = new ArrayCollection();
            $this->articles = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function setEmail(string $email): self
        {
            $this->email = $email;

            return $this;
        }

        /**
         * A visual identifier that represents this user.
         *
         * @see UserInterface
         */
        public function getUsername(): string
        {
            return (string)$this->email;
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

        public function setRoles(array $roles): self
        {
            $this->roles = $roles;

            return $this;
        }

        /**
         * @see UserInterface
         */
        public function getPassword(): string
        {
            return (string)$this->password;
        }

        public function setPassword(string $password): self
        {
            $this->password = $password;

            return $this;
        }

        /**
         * @see UserInterface
         */
        public function getSalt()
        {
            // not needed when using the "bcrypt" algorithm in security.yaml
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials()
        {
            // If you store any temporary, sensitive data on the user, clear it here
            // $this->plainPassword = null;
        }

        public function getFirstName(): ?string
        {
            return $this->firstName;
        }

        public function setFirstName(string $firstName): self
        {
            $this->firstName = $firstName;

            return $this;
        }

        public function getLastName(): ?string
        {
            return $this->lastName;
        }

        public function setLastName(?string $lastName): self
        {
            $this->lastName = $lastName;

            return $this;
        }

        public function getAgreedTermsAt(): ?\DateTimeInterface
        {
            return $this->agreedTermsAt;
        }

        public function agreeToTerms(): self
        {
            try {
                $this->agreedTermsAt = new \DateTime();
            } catch (\Exception $e) {
            }

            return $this;
        }

        public function setAgreedTermsAt(\DateTimeInterface $agreedTermsAt): self
        {
            $this->agreedTermsAt = $agreedTermsAt;

            return $this;
        }

        /**
         * @return Collection|Comment[]
         */
        public function getComments(): Collection
        {
            return $this->comments;
        }

        public function addComment(Comment $comment): self
        {
            if (!$this->comments->contains($comment)) {
                $this->comments[] = $comment;
                $comment->setUser($this);
            }

            return $this;
        }

        public function removeComment(Comment $comment): self
        {
            if ($this->comments->contains($comment)) {
                $this->comments->removeElement($comment);
                // set the owning side to null (unless already changed)
                if ($comment->getUser() === $this) {
                    $comment->setUser(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection|Article[]
         */
        public function getArticles(): Collection
        {
            return $this->articles;
        }

        public function addArticle(Article $article): self
        {
            if (!$this->articles->contains($article)) {
                $this->articles[] = $article;
                $article->setAuthor($this);
            }

            return $this;
        }

        public function removeArticle(Article $article): self
        {
            if ($this->articles->contains($article)) {
                $this->articles->removeElement($article);
                // set the owning side to null (unless already changed)
                if ($article->getAuthor() === $this) {
                    $article->setAuthor(null);
                }
            }

            return $this;
        }

        public function __toString()
        {
            if (!$this->getFirstName())
                return $this->getEmail();
            else
                return $this->getFirstName();
        }

        public function getPhoto(): ?UserPhoto
        {
            if ($this->photo == null) {
                $xd = new UserPhoto();
                $xd->setPath('img/avatars/avatar01.png');
                return $xd;
            }
            return $this->photo;
        }

        public function setPhoto(?UserPhoto $photo): self
        {
            $this->photo = $photo;

            return $this;
        }

        /**
         * @return Collection|Comment[]
         */
        public function getNonDeletedComments(): Collection
        {
            $criteria = CommentRepository::createNonDeletedCriteria();

            return $this->comments->matching($criteria);
        }
    }

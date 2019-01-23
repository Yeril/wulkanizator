<?php

    namespace App\Entity;

    use App\Repository\CommentRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Mapping\Annotation as Gedmo;
    use Gedmo\Timestampable\Traits\TimestampableEntity;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
     */
    class Article
    {
        use TimestampableEntity;

        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $title;

        /**
         * @ORM\Column(type="string", length=100, unique=true)
         * @Gedmo\Slug(fields={"title"})
         */
        private $slug;

        /**
         * @ORM\Column(type="text", nullable=true)
         */
        private $content;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         */
        private $publishedAt;

        /**
         * @ORM\Column(type="integer")
         */
        private $heartCount = 0;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article", fetch="EXTRA_LAZY")
         * @ORM\OrderBy({"createdAt" = "DESC"})
         *
         */
        private $comments;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles", fetch="EAGER")
         * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
         */
        private $author;

        /**
         * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="article")
         */
        private $tags;

        public function __construct()
        {
            $this->comments = new ArrayCollection();
            $this->tags = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getTitle(): ?string
        {
            return $this->title;
        }

        public function setTitle(string $title): self
        {
            $this->title = $title;

            return $this;
        }

        public function getSlug(): ?string
        {
            return $this->slug;
        }

        public function setSlug(string $slug): self
        {
            $this->slug = $slug;

            return $this;
        }

        public function getContent(): ?string
        {
            return $this->content;
        }

        public function setContent(?string $content): self
        {
            $this->content = $content;

            return $this;
        }

        public function getPublishedAt(): ?\DateTimeInterface
        {
            return $this->publishedAt;
        }

        public function setPublishedAt(?\DateTimeInterface $publishedAt): self
        {
            $this->publishedAt = $publishedAt;

            return $this;
        }

        public function getHeartCount(): ?int
        {
            return $this->heartCount;
        }

        public function incrementHeartCount(): self
        {
            $this->heartCount = $this->heartCount + 1;

            return $this;
        }

        public function decrementHeartCount(): self
        {
            $this->heartCount = $this->heartCount - 1;

            return $this;
        }

        public function setHeartCount($heartCount): self
        {
            $this->heartCount = $heartCount;

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
                $comment->setArticle($this);
            }

            return $this;
        }

        public function removeComment(Comment $comment): self
        {
            if ($this->comments->contains($comment)) {
                $this->comments->removeElement($comment);
                // set the owning side to null (unless already changed)
                if ($comment->getArticle() === $this) {
                    $comment->setArticle(null);
                }
            }

            return $this;
        }

        public function getAuthor(): ?User
        {
            return $this->author;
        }

        public function setAuthor(?User $author): self
        {
            $this->author = $author;

            return $this;
        }

        /**
         * @return Collection|Tag[]
         */
        public function getTags(): Collection
        {
            return $this->tags;
        }

        public function addTag(Tag $tag): self
        {
            if (!$this->tags->contains($tag)) {
                $this->tags[] = $tag;
                $tag->addArticle($this);
            }

            return $this;
        }

        public function removeTag(Tag $tag): self
        {
            if ($this->tags->contains($tag)) {
                $this->tags->removeElement($tag);
                $tag->removeArticle($this);
            }

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

        public function getPublishedAtString(): string
        {
            if ($this->getPublishedAt())
                return $this->getPublishedAt()->format("Y-m-d H:i:s");
            else
                return "null";
        }

        public function getCreatedAtString(): string
        {
            return $this->getCreatedAt()->format("Y-m-d H:i:s");
        }

        /**
         * @return bool
         */
        public function isPublished(): bool
        {
            if ($this->getPublishedAt())
                return true;
            else
                return false;
        }

        public function unpublish()
        {
            $this->publishedAt = null;
        }

        public function setTags($tags)
        {
            if ($tags) {
                $this->clearTags();
                /** @var Tag $tag */
                foreach ($tags as $tag) {
                    $this->tags[] = $tag;
                    $tag->addArticle($this);
                }
            }
        }

        public function clearTags()
        {
            /** @var Tag $tag */
            foreach ($this->tags as $tag) {
                $tag->removeArticle($this);
            }
            unset($this->tags);
            $this->tags = new ArrayCollection();
        }


    }

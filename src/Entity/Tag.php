<?php

    namespace App\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Mapping\Annotation as Gedmo;
    use Gedmo\Timestampable\Traits\TimestampableEntity;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
     */
    class Tag
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
         * @Assert\NotBlank(message="To pole nie może być puste.")
         */
        private $name;

        /**
         * @ORM\Column(type="string", length=255, unique=true)
         * @Gedmo\Slug(fields={"name"})
         */
        private $slug;

        /**
         * @ORM\ManyToMany(targetEntity="App\Entity\Article", inversedBy="tags")
         * @ORM\JoinColumn(onDelete="CASCADE")
         */
        private $article;

        public function __construct()
        {
            $this->article = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getName(): ?string
        {
            return $this->name;
        }

        public function setName(string $name): self
        {
            $this->name = $name;

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

        /**
         * @return Collection|Article[]
         */
        public function getArticle(): Collection
        {
            return $this->article;
        }

        public function addArticle(Article $article): self
        {
            if (!$this->article->contains($article)) {
                $this->article[] = $article;
            }

            return $this;
        }

        public function removeArticle(Article $article): self
        {
            if ($this->article->contains($article)) {
                $this->article->removeElement($article);
            }

            return $this;
        }

        public function getCreatedAtString()
        {
            return $this->getCreatedAt()->format("Y-m-d H:i:s");
        }

    }

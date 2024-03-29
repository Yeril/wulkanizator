<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Timestampable\Traits\TimestampableEntity;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
     */
    class Comment
    {
        use TimestampableEntity;
        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
         * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
         */
        private $user;

        /**
         * @ORM\Column(type="text")
         */
        private $content;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
         * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
         */
        private $article;

        /**
         * @ORM\Column(type="boolean")
         */
        private $isDeleted;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getUser(): ?User
        {
            return $this->user;
        }

        public function setUser(?User $user): self
        {
            $this->user = $user;

            return $this;
        }

        public function getContent(): ?string
        {
            return $this->content;
        }

        public function setContent(string $content): self
        {
            $this->content = $content;

            return $this;
        }

        public function getArticle(): ?Article
        {
            return $this->article;
        }

        public function setArticle(?Article $article): self
        {
            $this->article = $article;

            return $this;
        }

        public function getIsDeleted(): ?bool
        {
            return $this->isDeleted;
        }

        public function setIsDeleted(bool $isDeleted): self
        {
            $this->isDeleted = $isDeleted;

            return $this;
        }

        public function getCreatedAtString()
        {
            return $this->getCreatedAt()->format("Y-m-d H:i:s");
        }
    }

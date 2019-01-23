<?php

    namespace App\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\UserPhotoRepository")
     */
    class UserPhoto
    {
        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $path;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="photo")
         */
        private $user;

        public function __construct()
        {
            $this->user = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getPath(): ?string
        {
            if ($this->path)
                return $this->path;
            else
                return "/avatars/avatar01.png";
        }

        public function setPath(string $path): self
        {
            $this->path = $path;

            return $this;
        }

        /**
         * @return Collection|User[]
         */
        public function getUser(): Collection
        {
            return $this->user;
        }

        public function addUser(User $user): self
        {
            if (!$this->user->contains($user)) {
                $this->user[] = $user;
                $user->setPhoto($this);
            }

            return $this;
        }

        public function removeUser(User $user): self
        {
            if ($this->user->contains($user)) {
                $this->user->removeElement($user);
                // set the owning side to null (unless already changed)
                if ($user->getPhoto() === $this) {
                    $user->setPhoto(null);
                }
            }

            return $this;
        }
    }

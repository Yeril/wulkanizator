<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Timestampable\Traits\TimestampableEntity;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\ReceivedContactRepository")
     * @UniqueEntity(
     *     fields={"email"},
     *     message="Z tego maila została już wysłana wiadomość. Poczekaj na odpowiedź!"
     * )
     */
    class ReceivedContact
    {
        use TimestampableEntity;
        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=255, unique=true)
         * @Assert\NotBlank(message="Podaj swój email")
         * @Assert\Email()
         */
        private $email;

        /**
         * @ORM\Column(type="string", length=500)
         */
        private $content;

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

        public function getContent(): ?string
        {
            return $this->content;
        }

        public function setContent(string $content): self
        {
            $this->content = $content;

            return $this;
        }

        public function when()
        {
            return $this->getCreatedAt()->format("Y-m-d H:i:s");
        }
    }

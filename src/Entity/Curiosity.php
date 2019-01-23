<?php

    namespace App\Entity;

    use App\Validator\UniqueCuriosity;
    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Timestampable\Traits\TimestampableEntity;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


    /**
     * @ORM\Entity(repositoryClass="App\Repository\CuriosityRepository")
     * @UniqueEntity(fields={"title"}, message="Istnieje już taka ciekawostka!")
     */
    class Curiosity
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
         *
         */
        private $title;

        /**
         * @ORM\Column(type="string", length=255, unique=true)
         * @UniqueCuriosity()
         */
        private $text;

        /**
         * @ORM\Column(type="string", length=255, unique=true)
         * @UniqueCuriosity()
         */
        private $link;

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

        public function getText(): ?string
        {
            return $this->text;
        }

        public function setText(string $text): self
        {
            $this->text = $text;

            return $this;
        }

        public function getLink(): ?string
        {
            return $this->link;
        }

        public function setLink(string $link): self
        {
            $this->link = $link;

            return $this;
        }

        /**
         * @return string
         */
        public function when()
        {
            return $this->getCreatedAt()->format("Y-m-d H:i:s");
        }

//    public function isNewRecordIsUnique(Curiosity $curiosity)
//    {
//        $criteria = CuriosityRepository::createUniqueCriteria($curiosity);
//
//        $matching =
//    }
    }

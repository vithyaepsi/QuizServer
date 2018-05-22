<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="questions")
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Answer", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $answer;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuestionType", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question_type;



    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->rounds = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }


    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getQuestionType(): ?QuestionType
    {
        return $this->question_type;
    }

    public function setQuestionType(?QuestionType $question_type): self
    {
        $this->question_type = $question_type;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id"  =>  $this->getId(),
            "category"   =>  $this->getCategory(),
            //"matches" =>  $this->getMatches()->getValues(),
            "image" => $this->getImage()
        ];
    }


}

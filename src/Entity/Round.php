<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoundRepository")
 */
class Round implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Match", inversedBy="rounds")
     */
    private $matches;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question")
     */
    private $question;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Answer", inversedBy="rounds")
     */
    private $answers;


    public function __construct()
    {
        //$this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->question = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
    

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setRound($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getRound() === $this) {
                $answer->setRound(null);
            }
        }

        return $this;
    }

    public function getMatches(): ?Match
    {
        return $this->matches;
    }

    public function setMatches(?Match $matches): self
    {
        $this->matches = $matches;

        return $this;
    }


    public function jsonSerialize()
    {
        return [
            "id"  =>  $this->getId(),
            "question"   =>  $this->getQuestion(),
            "answers" =>  $this->getAnswers()->getValues()
        ];
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }


}

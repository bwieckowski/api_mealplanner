<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="planner")
 */
class PlannerItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="This filed can not be blank")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Meal", inversedBy="planner")
     * @ORM\JoinColumn(nullable=true)
     */
    private $meal;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("string",message="Field must be string")
     * @var string
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("integer",message="Field must be float")
     * @var integer
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="planner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * @param mixed $meal
     */
    public function setMeal($meal): void
    {
        $this->meal = $meal;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }




}
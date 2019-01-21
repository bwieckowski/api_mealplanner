<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="meal")
 */
class Meal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @var int
     */
    private $portions;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @var int
     */
    private $calory;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @var int
     */
    private $protein;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @var int
     */
    private $carbon;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @var int
     */
    private $fat;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @var float
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="meals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="MealProduct", mappedBy="meal")
     * @ORM\JoinColumn(nullable=true)
     */
    private $mealProduct;

    public function __construct()
    {
        //
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPortions(): int
    {
        return $this->portions;
    }

    public function setPortions(int $portions): void
    {
        $this->portions = $portions;
    }

    public function getCalory(): int
    {
        return $this->calory;
    }

    public function setCalory(int $calory): void
    {
        $this->calory = $calory;
    }

    public function getProtein(): int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): void
    {
        $this->protein = $protein;
    }

    public function getCarbon(): int
    {
        return $this->carbon;
    }

    public function setCarbon(int $carbon): void
    {
        $this->carbon = $carbon;
    }

    public function getFat(): int
    {
        return $this->fat;
    }

    public function setFat(int $fat): void
    {
        $this->fat = $fat;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser() : User
    {
        return $this->user;
    }


}
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Not be blank")
     * @Assert\Type("string",message="Field must be string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type("int",message="Field must be int")
     * @var int
     */
    private $calory;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type("int",message="Field must be int")
     * @var int
     */
    private $protein;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type("int",message="Field must be int")
     * @var int
     */
    private $carbon;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type("int",message="Field must be int")
     * @var int
     */
    private $fat;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     * @Assert\Type("float",message="Field must be float")
     * @var float
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Meal", mappedBy="products")
     */
    private $meals;

    public function __construct()
    {
        $this->meals = new ArrayCollection();
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

    public function getUser() : User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|Meal[]
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meals)
    {
        if (!$this->meals->contains($meals)) {
            $this->meals[] = $meals;
            $meals->addProduct($this);
        }
    }

    public function removeMeal(Meal $meals)
    {
        if ($this->meals->contains($meals)) {
            $this->meals->removeElement($meals);
            $meals->removeProduct($this);
        }
    }


}
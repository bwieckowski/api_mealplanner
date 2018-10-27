<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $calory;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $protein;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $carbon;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $fat;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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


}
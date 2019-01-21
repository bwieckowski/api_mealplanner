<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealProductRepository")
 * @ORM\Table(name="meal_product")
 */
class MealProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meal", inversedBy="mealProduct")
     * @ORM\JoinColumn(nullable=true)
     */
    private $meal;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="Mealproduct")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("float",message="Field must be float")
     * @var float
     */
    private $amount;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }


}
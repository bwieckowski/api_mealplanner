<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var type
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     */
    protected $products;

    /**
     * @var type
     * @ORM\OneToMany(targetEntity="Meal", mappedBy="user")
     */
    protected $meals;

    public function __construct()
    {
        parent::__construct();
        $this->products = new ArrayCollection();
        $this->meals = new ArrayCollection();
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
 * @return Collection | Product[]
 */
    public function getProducts() : Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product) :void
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }
    }
    public function removeProduct(Product $product) :void
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }
    }

    /**
     * @return Collection | Meal[]
     */
    public function getMeals() : Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meals) :void
    {
        if (!$this->meals->contains($meals)) {
            $this->meals[] = $meals;
            $meals->setUser($this);
        }
    }
    public function removeMeal(Meal $meals) :void
    {
        if ($this->meals->contains($meals)) {
            $this->meals->removeElement($meals);
            if ($meals->getUser() === $this) {
                $meals->setUser(null);
            }
        }
    }

}
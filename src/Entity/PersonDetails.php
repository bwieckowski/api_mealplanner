<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonDetailsRepository")
 * @ORM\Table(name="person_details")
 */
class PersonDetails
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
     * @Assert\Type("string",message="Field must be string")
     * @var string
     */
    private $sex;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type(type="float",message="Field must be float")
     * @var float
     */
    private $calory;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("float",message="Field must be float")
     * @var float
     */
    private $protein;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("float",message="Field must be float")
     * @var float
     */
    private $carbon;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("float",message="Field must be float")
     * @var float
     */
    private $fat;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("float",message="Field must be float")
     * @var float
     */
    private $weight;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="This filed can not be blank")
     * @Assert\Type("float",message="Field must be float")
     * @var float
     */
    private $height;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="details")
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
     * @return string
     */
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex(string $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return float
     */
    public function getCalory(): ?float
    {
        return $this->calory;
    }

    /**
     * @param float $calory
     */
    public function setCalory(float $calory): void
    {
        $this->calory = $calory;
    }

    /**
     * @return float
     */
    public function getProtein(): ?float
    {
        return $this->protein;
    }

    /**
     * @param float $protein
     */
    public function setProtein(float $protein): void
    {
        $this->protein = $protein;
    }

    /**
     * @return float
     */
    public function getCarbon(): ?float
    {
        return $this->carbon;
    }

    /**
     * @param float $carbon
     */
    public function setCarbon(float $carbon): void
    {
        $this->carbon = $carbon;
    }

    /**
     * @return float
     */
    public function getFat(): ?float
    {
        return $this->fat;
    }

    /**
     * @param float $fat
     */
    public function setFat(float $fat): void
    {
        $this->fat = $fat;
    }

    /**
     * @return float
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return float
     */
    public function getHeight(): ?float
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(float $height): void
    {
        $this->height = $height;
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
    public function setUser($user): void
    {
        $this->user = $user;
    }


}
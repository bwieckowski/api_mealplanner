<?php

namespace App\Tests\Service;

use App\Repository\MealRepository;
use App\Service\MealService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MealServiceTest
{
    private $emMock;
    private $mealRepositoryMock;
    private $paginatorMock;
    private $mealService;
    private $validatorMock;

    protected function setUp()
    {
        $this->mealRepositoryMock = $this->getMockBuilder(MealRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->emMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paginatorMock = $this->getMockBuilder(PaginatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mealService = new MealService(
            $this->mealRepositoryMock,
            $this->emMock,
            $this->paginatorMock,
            $this->validatorMock
        );
    }

    protected function tearDown()
    {
        $this->mealRepositoryMock = null;
        $this->emMock = null;
        $this->paginatorMock = null;
        $this->mealService = null;
    }

    public function testGetAllByUserId($id,$meals,$expected)
    {
        $this->mealRepositoryMock
            ->expects($this->once())
            ->method('getAllByUserId')
            ->with($id)
            ->willReturn($meals);

        $result = $this->mealService->getAllByUserId($id);

        $this->assertEquals($expected, $result);
    }
}
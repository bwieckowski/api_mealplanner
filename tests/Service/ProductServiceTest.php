<?php

namespace App\Tests\Service;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use App\Exception\ValidationException;
use App\Exception\BadRequestException;
use App\Exception\DeniedException;

class ProductServiceTest extends TestCase
{
    private $emMock;
    private $productRepositoryMock;
    private $paginatorMock;
    private $productService;
    private $validatorMock;

    protected function setUp()
    {
        $this->productRepositoryMock = $this->getMockBuilder(ProductRepository::class)
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

        $this->productService = new ProductService(
            $this->productRepositoryMock,
            $this->emMock,
            $this->paginatorMock,
            $this->validatorMock
        );

    }

    protected function tearDown()
    {
        $this->productRepositoryMock = null;
        $this->emMock = null;
        $this->paginatorMock = null;
        $this->productService = null;
    }

    /**
     * @dataProvider getAllByUserIdDataProvider
     */
    public function testGetAllByUserId($id,$products,$expected)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('getAllByUserId')
            ->with($id)
            ->willReturn($products);

        $result = $this->productService->getAllByUserId($id);

        $this->assertEquals($expected, $result);
    }

    public function testCreate()
    {
        $user = $this->createUser();
        $p1 = new Product();
        $p1->setName('Product 1');
        $p1->setCalory(100);
        $p1->setProtein(20);
        $p1->setCarbon(50);
        $p1->setFat(30);
        $p1->setWeight(100);
        $p1->setUser($user);

        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->with($p1)
            ->willReturn(null);

        $this->emMock
            ->expects($this->once())
            ->method('persist')
            ->with($p1);

        $this->emMock
            ->expects($this->once())
            ->method('flush');

        $data = [
            'name' => 'Product 1',
            'calory' => 100,
            'protein' => 20,
            'carbon' => 50,
            'fat' => 30,
            'weight' => 100
        ];
        $result = $this->productService->create($data,$user);

        $this->assertEquals($p1->getId(), $result);
        $this->assertEquals($p1->getName(), 'Product 1');
        $this->assertEquals($p1->getCalory(), 100);
        $this->assertEquals($p1->getProtein(), 20);
        $this->assertEquals($p1->getCarbon(), 50);
        $this->assertEquals($p1->getFat(), 30);
        $this->assertEquals($p1->getWeight(), 100);
        $this->assertEquals($p1->getUser(), $user);
    }

    public function testCreateWithInvalidData()
    {
        $user = $this->createUser();
        $p1 = new Product();
        $p1->setName('');
        $p1->setCalory(100);
        $p1->setProtein(20);
        $p1->setCarbon(50);
        $p1->setFat(30);
        $p1->setWeight(100);
        $p1->setUser($user);

        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->with($p1)
            ->willReturn(ConstraintViolationListInterface::class);

        $data = [
            'name' => '',
            'calory' => 100,
            'protein' => 20,
            'carbon' => 50,
            'fat' => 30,
            'weight' => 100
        ];
        $this->expectException(ValidationException::class);
        $this->productService->create($data,$user);

    }

    public function testUpdate()
    {
        $user = $this->createUser();
        $product = new Product();
        $product->setId(1);
        $product->setName('Product 1');
        $product->setCalory(100);
        $product->setProtein(20);
        $product->setCarbon(50);
        $product->setFat(30);
        $product->setWeight(100);
        $product->setUser($user);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('getOneById')
            ->with(1)
            ->willReturn($product);

        $this->emMock
            ->expects($this->once())
            ->method('flush');

        $data = [
            'name' => 'Product 1',
            'calory' => 100,
            'protein' => 20,
            'carbon' => 50,
            'fat' => 30,
            'weight' => 100
        ];
        $this->productService->update($data,1,$user);

        $this->assertEquals($product->getName(), 'Product 1');
        $this->assertEquals($product->getCalory(), 100);
        $this->assertEquals($product->getProtein(), 20);
        $this->assertEquals($product->getCarbon(), 50);
        $this->assertEquals($product->getFat(), 30);
        $this->assertEquals($product->getWeight(), 100);
        $this->assertEquals($product->getUser(), $user);
    }

    public function testUpdateShouldThrowExceptionForInvalidProductId()
    {
        $id = 225;
        $data = [
            'name' => 'Product 1',
            'calory' => 100,
            'protein' => 20,
            'carbon' => 50,
            'fat' => 30,
            'weight' => 100
        ];
        $user = $this->createUser();
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('getOneById')
            ->with($id)
            ->willReturn(null);
        $this->expectException(BadRequestException::class);
        $this->productService->update($data,$id,$user);
    }

    public function testUpdateShouldThrowExceptionForBadUser()
    {
        $user = $this->createUser();
        $user2 = new User();
        $user2->setId(2);
        $product = new Product();
        $product->setId(1);
        $product->setName('Product 1');
        $product->setCalory(100);
        $product->setProtein(20);
        $product->setCarbon(50);
        $product->setFat(30);
        $product->setWeight(100);
        $product->setUser($user);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('getOneById')
            ->with(1)
            ->willReturn($product);

        $data = [
            'name' => 'Product 1',
            'calory' => 100,
            'protein' => 20,
            'carbon' => 50,
            'fat' => 30,
            'weight' => 100
        ];
        $this->expectException(DeniedException::class);
        $this->productService->update($data,1,$user2);
    }

    public function testDelete()
    {
        $user = $this->createUser();
        $product = new Product();
        $product->setId(1);
        $product->setName('Product 1');
        $product->setCalory(100);
        $product->setProtein(20);
        $product->setCarbon(50);
        $product->setFat(30);
        $product->setWeight(100);
        $product->setUser($user);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('getOneById')
            ->with(1)
            ->willReturn($product);

        $this->emMock
            ->expects($this->once())
            ->method('remove')
            ->with($product);

        $this->emMock
            ->expects($this->once())
            ->method('flush');

        $this->productService->delete(1,$user);
    }

    public function testDeleteShouldThrowExceptionForInvalidProductId()
    {
        $id = 225;
        $user = $this->createUser();
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('getOneById')
            ->with($id)
            ->willReturn(null);
        $this->expectException(BadRequestException::class);
        $this->productService->delete($id,$user);
    }

    public function testDeleteShouldThrowExceptionForBadUser()
    {
        $user = $this->createUser();
        $user2 = new User();
        $user2->setId(2);
        $product = new Product();
        $product->setId(1);
        $product->setName('Product 1');
        $product->setCalory(100);
        $product->setProtein(20);
        $product->setCarbon(50);
        $product->setFat(30);
        $product->setWeight(100);
        $product->setUser($user);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('getOneById')
            ->with(1)
            ->willReturn($product);

        $this->expectException(DeniedException::class);
        $this->productService->delete(1,$user2);
    }

    public function getAllByUserIdDataProvider()
    {
        $user = $this->createUser();

        $p1 = new Product();
        $p1->setId(1);
        $p1->setName('Product 1');
        $p1->setCalory(100);
        $p1->setProtein(20);
        $p1->setCarbon(50);
        $p1->setFat(30);
        $p1->setWeight(100);
        $p1->setUser($user);

        $product1 = [$p1];

        $p2 = new Product();
        $p2->setId(2);
        $p2->setName('Product 2');
        $p2->setCalory(200);
        $p2->setProtein(50);
        $p2->setCarbon(110);
        $p2->setFat(30);
        $p2->setWeight(200);
        $p2->setUser($user);

        $product2 = [$p1,$p2];

        return [
            [1, $product1, $product1],
            [1, $product2, $product2],
        ];
    }

    public function createUser()
    {
        $user = new User();
        $user->setId(1);

        return $user;
    }

}
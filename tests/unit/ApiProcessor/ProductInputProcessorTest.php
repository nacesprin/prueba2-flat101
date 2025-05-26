<?php

declare(strict_types=1);

namespace App\Tests\Unit\ApiProcessor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use App\Application\DTO\ProductInput;
use App\Domain\Entity\Product;
use App\Infrastructure\Exception\ElementoExisteException;
use App\Infrastructure\Processor\ProductInputProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class ProductInputProcessorTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;
    private ProductInputProcessor $processor;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(Product::class)
            ->willReturn($this->repository);

        $this->processor = new ProductInputProcessor($this->entityManager);
    }

    public function testPostCreaNuevoProducto(): void
    {
        $input = new ProductInput();
        $input->name = 'Nuevo Producto';
        $input->price = 10.0;

        $this->repository
            ->method('findOneBy')
            ->with(['name' => 'Nuevo Producto'])
            ->willReturn(null);

        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $product = $this->processor->process($input, new Post());

        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame('Nuevo Producto', $product->getName());
        $this->assertSame(10.0, $product->getPrice());
    }

    public function testPostExcepcionSiProductoExiste(): void
    {
        $this->expectException(ElementoExisteException::class);
        $this->expectExceptionMessage('Producto ya existe');

        $input = new ProductInput();
        $input->name = 'Existente';

        $this->repository
            ->method('findOneBy')
            ->with(['name' => 'Existente'])
            ->willReturn($this->createMock(Product::class));

        $this->processor->process($input, new Post());
    }

    public function testDelete(): void
    {
        $product = $this->createMock(Product::class);

        $this->entityManager->expects($this->once())->method('remove')->with($product);
        $this->entityManager->expects($this->once())->method('flush');

        $result = $this->processor->process($product, new Delete());

        $this->assertSame($product, $result);
    }
}

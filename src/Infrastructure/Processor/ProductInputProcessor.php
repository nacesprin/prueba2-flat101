<?php
declare(strict_types=1);
namespace App\Infrastructure\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Application\DTO\ProductInput;
use App\Domain\Entity\Product;
use App\Infrastructure\Exception\ElementoExisteException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductInputProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    /**
     * Procesador para manejar la entrada de datos de productos.
     *
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * 
     * @throws BadRequestException Si el método no es soportado.
     * @return Product
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Product
    {
        if ($operation instanceof Post) {
            return $this->post($data);
        }
        if ($operation instanceof Patch) {
            return $this->patch($data, $uriVariables);
        }
        if ($operation instanceof Delete) {
            return $this->delete($data);
        }
        throw new BadRequestException('Método no soportado');
    }
    
    /**
     * Maneja la creación de un nuevo producto.
     * Si existe un producto con el mismo nombre, lanza una excepción.
     *
     * @param ProductInput $data
     * 
     * @throws ElementoExisteException Para los casos en que el producto ya existe.
     * @return Product
     */
    private function post(ProductInput $data): Product
    {
        $repo = $this->entityManager->getRepository(Product::class);
        $product = $repo->findOneBy(['name' => $data->name]);
        if ($product) {
            throw new ElementoExisteException('Producto ya existe');
        }
        $product = new Product();
        $product->setName($data->name);
        $product->setPrice($data->price);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }

    /**
     * Actualiza un producto existente.
     * Realiza comprobaciones para asegurarse de que el nombre del producto no exista ya.
     *
     * @param ProductInput $data
     * @param array $uriVariables
     * 
     * @throws BadRequestException Si el ID no se proporciona.
     * @throws ElementoExisteException Si el producto ya existe con el mismo nombre.
     * @throws NotFoundHttpException Si el producto no se encuentra por ID.
     * @return Product
     */
    private function patch(ProductInput $data, array $uriVariables): Product
    {
        $id = $uriVariables['id'] ?? null;
        if ($id === null) {
            throw new BadRequestException('ID requerido');
        }

        // Comprobamos si el nombre del producto ya existe, excluyendo el producto actual
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->neq('id', $id));
        $criteria->andWhere(Criteria::expr()->eq('name', $data->name));
        $qb = $this->entityManager->getRepository(Product::class)->createQueryBuilder('p');
        $qb->addCriteria($criteria);
        $product = $qb->getQuery()->getResult();
        if ($product) {
            throw new ElementoExisteException('Producto ya existe');
        }
        
        // Comprobamos si el producto por ID existe
        $product = $this->entityManager->find(Product::class, $id);
        if (!$product) {
            throw new NotFoundHttpException('Producto no encontrado');
        }
        
        // Todo correcto, actualizamos los campos
        if ($data->name !== null) {
            $product->setName($data->name);
        }
        if ($data->price !== null) {
            $product->setPrice($data->price);
        }
        $this->entityManager->flush();
        return $product;
    }
    private function delete(Product $product): Product
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return $product;
    }
}

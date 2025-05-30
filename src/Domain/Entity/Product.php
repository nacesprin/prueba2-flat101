<?php

namespace App\Domain\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Application\DTO\ProductInput;
use App\Application\DTO\ProductOutput;
use App\Domain\Repository\ProductRepository;
use App\Infrastructure\Processor\ProductInputProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    // input es para indicar qué clase se usa para recibir los datos al crear o actualizar un recurso
    input: ProductInput::class,
    // processor es el procesador que se usará para manejar la entrada de datos
    // en este caso, usamos un procesador personalizado que transforma el DTO a la entidad
    processor: ProductInputProcessor::class,
    // denormalizationContext son los grupos usados para serializar los datos al escribirlos (enviar datos)
    denormalizationContext: ['groups' => ['product:escritura']],
    
    // output es para indicar qué clase se usa para serializar los datos al leer un recurso
    output: ProductOutput::class,
    // normalizationContext son los grupos usados para serializar los datos al leerlos (mostrarlos)
    normalizationContext: ['groups' => ['product:lectura']],
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['product:lectura'])] // Reconozco que esto debería estar en el DTO ProductOutput para separar la lógica de la Entity, pero no he podido dar con la clave
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['product:lectura'])] // Reconozco que esto debería estar en el DTO ProductOutput para separar la lógica de la Entity, pero no he podido dar con la clave
    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct()
    {
        // Inicializamos created_at con la fecha y hora actual para que nunca haya que definirla manualmente
        // Usar \DateTimeImmutable ya que es una fecha que no cambia
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}

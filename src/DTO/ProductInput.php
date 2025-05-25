<?php
declare(strict_types=1);
namespace App\DTO;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class ProductInput
{
    #[Groups(['product:escritura'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, minMessage: "El nombre debe tener al menos {{ limit }} caracteres.")]
    public ?string $name = null;

    #[Groups(['product:escritura'])]
    #[Assert\NotNull]
    #[Assert\Positive(message: "El precio debe ser un número positivo.")]
    public ?float $price = null;
}
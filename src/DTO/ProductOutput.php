<?php
declare(strict_types=1);
namespace App\DTO;
use Symfony\Component\Serializer\Attribute\Groups;
final class ProductOutput
{
    #[Groups(['product:lectura'])]
    public ?int $id = null;

    #[Groups(['product:lectura'])]
    public ?string $name = null;

    #[Groups(['product:lectura'])]
    public ?float $price = null;

    #[Groups(['product:lectura'])]
    public ?\DateTimeImmutable $createdAt = null;
}
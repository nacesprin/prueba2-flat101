<?php
declare(strict_types=1);

namespace App\Infrastructure\Exception;

use ApiPlatform\Metadata\Exception\ProblemExceptionInterface;

/**
 * Excepción personalizada lanzada cuando se intenta acceder a un elemento que ya existe
 */
final class ElementoExisteException extends \Exception implements ProblemExceptionInterface
{
    public function getStatus(): ?int
    {
        return 419;
    }

    public function getType(): string
    {
        return 'yaexiste';
    }

    public function getTitle(): ?string
    {
        return 'Elemento ya existe';
    }

    public function getDetail(): ?string
    {
        return $this->getMessage();
    }

    public function getInstance(): ?string
    {
        return null;
    }
}

<?php
declare(strict_types=1);
namespace App\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

final class ApiException extends BadRequestException
{
    public function __construct(string $message = 'Solicitud Incorrecta')
    {
        parent::__construct($message);
    }
}
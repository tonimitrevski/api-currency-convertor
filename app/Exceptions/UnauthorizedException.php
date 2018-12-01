<?php
namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    public function __construct(string $message = 'unauthorized_exception', $code = 401)
    {
        parent::__construct($code, $message);
    }
}

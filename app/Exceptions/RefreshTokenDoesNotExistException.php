<?php
namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class RefreshTokenDoesNotExistException extends HttpException
{
    public function __construct(string $message = 'refresh_token_not_exist', $code = 401)
    {
        parent::__construct($code, $message);
    }
}

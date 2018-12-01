<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\TokensResource;
use App\Services\LoginService;

class LoginController extends Controller
{
    /**
     * @param LoginService $loginService
     * @param LoginRequest $request
     * @return mixed
     * @throws \App\Exceptions\RefreshTokenDoesNotExistException
     * @throws \App\Exceptions\UnauthorizedException
     * @throws \Exception
     */
    public function __invoke(
        LoginService $loginService,
        LoginRequest $request
    ): TokensResource {
        $tokens = $loginService->login(collect($request->all()));
        return new TokensResource($tokens);
    }
}

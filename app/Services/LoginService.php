<?php
namespace App\Services;

use App\Exceptions\RefreshTokenDoesNotExistException;
use App\Exceptions\UnauthorizedException;
use App\Repositories\User\UserRepositoryContract;
use App\Models\User;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService
{
    private $userRepository;
    private $refreshTokenService;

    /**
     * Create a new AuthController instance.
     *
     * @param UserRepositoryContract $userRepository
     * @param RefreshTokenService    $refreshTokenService
     */
    public function __construct(
        UserRepositoryContract $userRepository,
        RefreshTokenService $refreshTokenService
    ) {
        $this->userRepository = $userRepository;
        $this->refreshTokenService = $refreshTokenService;
    }

    /**
     * @param $data
     * @return array
     * @throws RefreshTokenDoesNotExistException
     * @throws UnauthorizedException
     * @throws \Exception
     */
    public function login(Collection $data): array
    {
        if ($data->get('grant_type') === 'password') {
            return $this->password($data);
        }

        if ($data->get('grant_type') === 'refresh_token') {
            return $this->refreshToken($data);
        }

        throw new UnauthorizedException('invalid_credentials');
    }

    /**
     * @param $request
     * @return array
     * @throws UnauthorizedException
     * @throws \Exception
     */
    private function password(Collection $request): array
    {
        $credentials = $request->only(['email', 'password'])->toArray();

        if (! $token = auth()->setTTL(config('jwt.ttl'))->attempt($credentials)) {
            throw new UnauthorizedException('invalid_credentials');
        }
        /** @var User $user */
        $user = auth()->userOrFail();
        // Update last_login_at in Db
        $this->userRepository->updateLastLoginAt($user);
        // Store in Redis
        $redisToken = $this->refreshTokenService->newRefreshTokenDependOn($request->get('remember'), $user->toArray());

        return [
            'access_token' => $token,
            'refresh_token' => $redisToken['refresh_token']
        ];
    }

    /**
     * @param $data
     * @return array
     * @throws RefreshTokenDoesNotExistException
     */
    private function refreshToken(Collection $data): array
    {
        if (!$this->refreshTokenService->checkIfRefreshTokenExist($refresh_token = $data->get('refresh_token'))) {
            throw new RefreshTokenDoesNotExistException();
        }

        $redisResults = $this->refreshTokenService->updateAndReturnTheToken($refresh_token);
        $user = $this->userRepository->findOrFail($redisResults['id_user']);
        $newToken = JWTAuth::fromUser($user);
        $this->userRepository->updateLastLoginAt($user);
        return ['access_token' => $newToken];
    }
}

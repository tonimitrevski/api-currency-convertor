<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;

class RefreshTokenService
{
    /**
     * Get the user_refresh_tokens cache key name.
     *
     * @return string
     */
    public function usersRefreshTokensCacheKey()
    {
        return app()->environment('testing')
            ? 'testing_user_refresh_tokens'
            : 'user_refresh_tokens';
    }

    /**
     * Reset all keys in redis.
     */
    public function flushAllKeys()
    {
        Redis::flushall();
    }

    public function newRefreshTokenDependOn($isChecked, $userArray): array
    {
        $userArray['refresh_token_expires_at'] = Carbon::now()->addHours(24); // 24 hours

        if ($this->rememberMe($isChecked)) {
            $userArray['refresh_token_expires_at'] = Carbon::now()->addYears(2); // 2 years
        }

        $token = $this->makeNewRefreshTokenWithExpiration($userArray);

        $this->addRefreshTokensToTheUserList($token);

        return $token;
    }

    /**
     * Make a new refresh_token
     * hmset refresh_token:abc123 id 1 refresh_token abc123 last_login_at 34657 refresh_token_expires_at 2222
     * Set expiration 1528143986 => strtotime("+8 hours"); or 1685881629 =>strtotime("+5 years")
     * expireat refresh_token:abc123 1528143986
     */
    private function makeNewRefreshTokenWithExpiration($redisArray)
    {
        $random_refresh_token = str_random(20); // TODO should have better algorithm maybe
        $dataToInsert = [
            'id' => $redisArray['id'],
            'refresh_token' => $random_refresh_token,
            'created_at' => Carbon::now()->toDateTimeString(),
            'refresh_token_expires_at' => $redisArray['refresh_token_expires_at']->toDateTimeString(),
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'device_info' => Request::header('user-agent')
        ];

        Redis::hmset("{$random_refresh_token}", $dataToInsert);

        Redis::expireat(
            $random_refresh_token,
            $redisArray['refresh_token_expires_at']->timestamp
        );

        return $dataToInsert;
    }

    /**
     * @param $refresh_token
     */
    public function deleteTheRefreshToken($refresh_token)
    {
        Redis::del((string) $refresh_token);
    }

    public function checkAndRemoveFromUserList($redisArray)
    {
        return Redis::lrem(
            "{$this->usersRefreshTokensCacheKey()}:{$redisArray['id']}",
            -1,
            "{$redisArray['refresh_token']}"
        );
    }

    public function getAllTokensForTheUserList($redisArray)
    {
        return Redis::lrange(
            "{$this->usersRefreshTokensCacheKey()}:{$redisArray['id']}",
            0,
            -1
        );
    }

    /**
     * Update last_login_at for the same refresh_token
     * hset abc123 last_login_at '2023-06-01 08:45:44'
     */
    public function updateLastLoginAtFor($refresh_token)
    {
        return Redis::hset(
            (string) $refresh_token,
            'last_login_at',
            (string) Carbon::now()->toDateTimeString() // last_login_at shlould be updated
        );
    }

    /**
     * Get that token by the key
     * hgetall refresh_token:abc123
     */
    public function getTheToken($refresh_token): array
    {
        return Redis::hgetall((string) $refresh_token);
    }

    public function updateAndReturnTheToken($refresh_token): array
    {
        $this->updateLastLoginAtFor($refresh_token);

        return $this->getTheToken($refresh_token);
    }

    /**
     * Check if refresh token exist (return integer 0 or 1)
     * exists refresh_token:abc123
     */
    public function checkIfRefreshTokenExist($refresh_token): bool
    {
        return (bool) Redis::exists((string) $refresh_token);
    }

    /**
     * Make a list for specific user
     * rpush user:1 refresh_token:abc123
     */
    private function makeAListForTheUser($redisArray)
    {
        return Redis::rpush(
            "{$this->usersRefreshTokensCacheKey()}:{$redisArray['id']}",
            "{$redisArray['refresh_token']}"
        );
    }

    /**
     * Add another refresh_token in the same user (same list)
     * rpush user:1 refresh_token:abc123
     * After every push to the users list if return > 5 we should
     * lpop user:1
     */
    public function addRefreshTokensToTheUserList($redisArray)
    {
        $numberOfTokens = $this->makeAListForTheUser($redisArray);

        if ((int) $numberOfTokens > 5) {
            $tokenDeletedFromList = Redis::lpop("{$this->usersRefreshTokensCacheKey()}:{$redisArray['id']}");

            // invalidate deleted token from the list
            Redis::del($tokenDeletedFromList);
        }
    }

    /**
     * @param $isChecked
     * @return bool
     */
    private function rememberMe($isChecked): bool
    {
        return (bool) $isChecked === true;
    }
}

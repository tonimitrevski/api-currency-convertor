<?php
namespace Tests\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class DummyRedisData
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

    public function seedValidRefreshTokenDependOn($redisArray)
    {
        Redis::flushall();

        $dataToInsert = [
            'id_user' => $redisArray['id_user'],
            'refresh_token' => $random_refresh_token = $redisArray['refresh_token'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'refresh_token_expires_at' => $redisArray['refresh_token_expires_at'] = Carbon::now()->addHours(8)->toDateTimeString(),
            'last_login_at' => '2000-01-01 00:00:00',
            'device_info' => 'From Internet Explorer? Not...'
        ];

        Redis::hmset((string) $random_refresh_token, $dataToInsert);

        Redis::expireAt(
            $random_refresh_token,
            Carbon::parse($redisArray['refresh_token_expires_at'])->timestamp
        );

        $this->addRefreshTokensToTheUserList($redisArray);

        Redis::rpush(
            "{$this->usersRefreshTokensCacheKey()}:{$redisArray['id_user']}",
            "dsad88sd8a8sd8sdaad"
        );

        return $dataToInsert;
    }

    /**
     * Add another refresh_token in the same user (same list)
     * rpush user:1 refresh_token:abc123
     * After every push to the users list if return > 5 we should
     * lpop user:1
     */
    public function addRefreshTokensToTheUserList($redisArray)
    {
        $numberOfTokens = Redis::rpush(
            "{$this->usersRefreshTokensCacheKey()}:{$redisArray['id_user']}",
            "{$redisArray['refresh_token']}"
        );

        if ((int) $numberOfTokens > 5) {
            return Redis::lpop("{$this->usersRefreshTokensCacheKey()}:{$redisArray['id_user']}");
        }
    }
}

<?php
namespace App\Repositories\User;

use App\Models\User;
use Carbon\Carbon;

class UserRepository implements UserRepositoryContract
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $user
     */
    public function updateLastLoginAt($user): void
    {
        $user->last_login_at = Carbon::now()->toDateTimeString();
        $user->save();
    }

    /**
     * @param array $userData
     */
    public function create(array $userData): void
    {
        /** @var User $user */
        $user = new User($userData);
        $user->save();
    }

    /**
     * @param $idUser
     * @param array $updatedData
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update($idUser, array $updatedData): void
    {
        /** @var User $user */
        $user = $this->user->newQuery()->findOrFail($idUser);
        $user->fill($updatedData);
        $user->save();
    }

    /**
     * @param $idUser
     * @return int
     */
    public function delete($idUser): int
    {
        return $this->user->where('id', $idUser)->delete();
    }

    public function findOrFail($idUser)
    {
        return $this->user->newQuery()->findOrFail($idUser);
    }
}

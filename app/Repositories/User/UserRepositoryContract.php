<?php
namespace App\Repositories\User;

interface UserRepositoryContract
{
    public function findOrFail($id);

    public function updateLastLoginAt($user): void;

    public function update($idUser, array $updatedData): void;

    public function delete($id): int;
}

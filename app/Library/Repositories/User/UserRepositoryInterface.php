<?php namespace News\Repositories\User;

use News\Abstracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function create($data);

    public function update($data,$id);

    public function getByEmail($email);

    public function resetPassword($data);
}

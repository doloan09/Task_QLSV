<?php

namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    //ví dụ: lấy 5 user đầu tiên
    public function getUser();

    public function deleteAvatar($id);

    public function filterByRole($request);

    public function topStudent($request);

    public function getInfo($request);

}

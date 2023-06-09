<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\User::class;
    }

    public function getUser()
    {
        return $this->model->select('id', 'name', 'email')->take(5)->get();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteAvatar($id)
    {
        $user = $this->model->find($id);
        if ($user->avatar){
            return Storage::delete($user->avatar);
        }

        return false;
    }

    public function filterByRole($request)
    {
        $list = $this->model->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*', 'roles.name as name_role');

        if ($request['role'])       $list = $list->where('roles.name', $request['role']);
        if ($request['code_user'])  $list = $list->where('code_user', $request['code_user']);
        if ($request['id_user'])  $list = $list->where('users.id', $request['id_user']);

        return $list->get();
    }

    public function topStudent($request)
    {
        $list = $this->model->join('points', 'points.id_user', '=', 'users.id')
            ->select('users.id', 'name','avatar', 'code_user', 'email', 'date_of_birth', 'sex', DB::raw('round(AVG(score_final), 1) as diemTB'))
            ->groupBy('users.id')
            ->orderBy('diemTB', 'desc')
            ->take(5)
            ->get();

        return $list;
    }

    public function getInfo($request)
    {
        $list = $this->model->join('points', 'points.id_user', '=', 'users.id')
            ->select('users.id', DB::raw('COUNT(points.id_user) as sumClass'), DB::raw('round(AVG(score_final), 1) as diemTB'))
            ->where('users.id', $request['id_user'])
            ->groupBy('users.id')
            ->get();

        return $list;
    }
}

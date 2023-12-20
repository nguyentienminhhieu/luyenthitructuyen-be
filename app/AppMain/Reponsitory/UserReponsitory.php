<?php

namespace App\AppMain\Reponsitory;
use App\Models\User;

class UserReponsitory extends  BaseRepository{
    
    public function getModel()
    {
        return User::class;
    }

    public function getQueryBuilder()
    {
        return User::query();
    }

    public function create($input) 
    {
        $query = $this->getQueryBuilder();
        return $query->create($input);
    }

    public function getUsers($inputs)
    {
        $query = $this->getQueryBuilder();
        if(isset($inputs['username']) && $inputs['username'] != '') {
            $query->where('name','LIKE', '%'.$inputs['username'].'%');
        }
        if(isset($inputs['grade_id']) && $inputs['grade_id'] != null) {
            $query->where('grade_id','=', $inputs['grade_id']);
        }
        $query->with('grade');
        return $query->paginate($inputs['limit']??10);
    }
}
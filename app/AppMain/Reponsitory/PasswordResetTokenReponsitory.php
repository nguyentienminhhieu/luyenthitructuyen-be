<?php

namespace App\AppMain\Reponsitory;
use App\Models\PasswordResetToken;

class PasswordResetTokenReponsitory extends  BaseRepository{
    
    public function getModel()
    {
        return PasswordResetToken::class;
    }

    public function getQueryBuilder()
    {
        return PasswordResetToken::query();
    }

    public function create($input) 
    {
        $query = $this->getQueryBuilder();
        return $query->create($input);
    }
}
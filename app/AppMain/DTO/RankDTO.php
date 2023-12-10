<?php

namespace App\AppMain\DTO;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RankDTO
{
    public array $grades;

    public function __construct(array $grades = [])
    {
        $this->grades = $grades;
    }

    public function formatData()
    {
        $newData = [];
        foreach($this->grades as $key => &$grade  ) {
            
        }
        return $this->grades;
    }
}
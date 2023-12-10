<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class getRankByGrade implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $result;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         // Xử lý công việc của bạn
         $result = 'Dữ liệu bạn muốn trả về';

         // Lưu giá trị vào thuộc tính để sử dụng sau này
         $this->result = $result;
    }


    public function getResult()
    {
        if(isset($this->result) && $this->result !== null) {
            return $this->result;
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function getNIK()
    {
        # code...
        $nik = 'E-'.mt_rand(100000, 999999);
        $cek = DB::table('tb_employee')->where('nik',$nik)->exists();
        if($cek){
            $this->getNIK();
        }
        return $nik;
    }
}

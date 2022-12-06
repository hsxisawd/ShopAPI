<?php

namespace App\Http\Controllers\file;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class fileController extends BaseController
{
    public function up_files(Request $request){
        $file=$request->file('file');
        if (is_array($file)){
            $file_name=[];
            foreach ($file as $img){
                $file_name[]=$this->file_name($img);
            };
            return $file_name;
        }else{
            return $this->file_name($file);
        }
    }

    public function get_files_url(Request  $request){
        $file_name=$request->input('file_name');
        return env('APP_URL').Storage::url($file_name);
    }




    protected function file_name($file)
    {
       return substr($file->store('public'),7);
    }
}

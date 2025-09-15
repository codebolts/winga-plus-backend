<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message='Operation successful',$data=null,$statusCode=200)
    {
        return response()->json([
            'status'=>'success',
            'message'=>$message,
            'data'=>$data
        ],$statusCode);
    }

    public static function error($message='An error occurred',$data=null,$statusCode=400)
    {
        return response()->json([
            'status'=>'error',
            'message'=>$message,
            'data'=>$data
        ],$statusCode);
    }
}

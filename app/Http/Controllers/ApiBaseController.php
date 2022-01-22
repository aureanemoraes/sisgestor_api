<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    public function response($success, $data, $status) {
        return response()->json(
            [
                'data' => $data,
                'sucess' => $success
            ], 
            $status
        );
    }
}

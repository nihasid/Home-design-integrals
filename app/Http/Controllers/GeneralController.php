<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHandler;

class GeneralController extends Controller
{
    function welcome () {
        return ResponseHandler::success();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * @return int
     */
    protected function getPagination()
    {
        return 10;
    }

    protected function getEagerLoad()
    {
        return [
            'image'
        ];
    }
}

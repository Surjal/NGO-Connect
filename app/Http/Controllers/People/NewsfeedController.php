<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsfeedController extends Controller
{
    public function index()
    {
        return app(\App\Http\Controllers\Common\FeedController::class)->index();
    }
}

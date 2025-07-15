<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frame;

class GuestController extends Controller
{
    public function shoot()
    {
        $data = Frame::where('type', 'single')->first();
        return view('guest.shoot', [
            'data' => $data,
        ]);
    }
}

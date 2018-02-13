<?php

namespace App\Http\Controllers\WebApi;

use App\Archive;
use App\Http\ReturnHelper;
use App\Transformers\ArchiveTransformer;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArchiveController extends Controller
{
    public function show()
    {
        return ReturnHelper::returnWithStatus(Fractal::collection(Archive::all(),new ArchiveTransformer()) );
    }
}

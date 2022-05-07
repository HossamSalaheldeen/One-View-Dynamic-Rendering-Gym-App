<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UploadController extends Controller
{
    /**
     * @param $path
     * @return Application|ResponseFactory|Response
     * @throws FileNotFoundException
     * show uploaded file request handler
     */
    public function show($path)
    {
        $file = Storage::disk('local')->get($path);
        $mime = Storage::disk('local')->mimeType($path);
        return response($file)->header('Content-Type',$mime);
    }

    /**
     * @param $path
     * @return StreamedResponse
     * download uploaded file request handler
     */
    public function download($path)
    {
        return Storage::download($path);
    }

}

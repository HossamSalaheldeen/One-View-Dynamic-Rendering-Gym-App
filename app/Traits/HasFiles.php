<?php

namespace App\Traits;


use App\Http\Controllers\UploadController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

trait HasFiles
{
    public function storeFile($dirName, $file, $fileName, $parent = NULL)
    {
        $path = $parent ? $dirName . '/' . $parent->id : $dirName;
        $path = Storage::putFileAs($path, $file,$fileName);
        return $path;
    }

    public function storeBase64($encrypted_string, $dir, $parent = NULL)
    {
        $image       = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $encrypted_string));
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($tmpFilePath, $image);
        $image = new File($tmpFilePath);
        $image = new UploadedFile(
            $image->getPathname(),
            $image->getFilename(),
            $image->getMimeType(),
            0,
            true
        );
        return $this->storeFile($dir, $image, $parent);
    }

    public function getFileUrl($path): ?string
    {
        if ($path)
            return Storage::url($path);
        return null;
    }

    public function getFileSize($path)
    {
        if ($path)
            return Storage::size($path);
        return null;
    }

    public function deleteFile($path): ?bool
    {
        if ($path)
            return Storage::delete($path);
        return null;
    }

    public function getMimeType($path)
    {
        if ($path)
            return Storage::mimeType($path);
        return null;
    }

    public function getDownloadFileUrl($path): ?string
    {
        if ($path)
            return action([UploadController::class, 'download'], ['path' => $path]);
        return null;
    }

    /**
     * @param string $directory
     * @param $child
     */
    private function deleteDirectory(string $directory, $child = NULL)
    {
        Storage::deleteDirectory("$directory/$child");
    }
}

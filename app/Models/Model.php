<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    protected $guarded = [];

    protected $perPage = 10;

    public function resolveRouteBinding($value)
    {
        return in_array(SoftDeletes::class, class_uses($this))
            ? $this->where($this->getRouteKeyName(), $value)->withTrashed()->first()
            : parent::resolveRouteBinding($value);
    }

    public function uploadFile($file, $identifier, $folder)
    {
       $this->generateFilePath($identifier, $folder);
        //Storage::makeDirectory($directory);
        list($type, $format) = explode('/', $file->getMimeType());
        $fileName = sprintf('%s.%s',
            $identifier,
            $format
        );

        return Storage::putFileAs($folder, $file, $fileName);
    }

    public function getUploadedFile($fileName, $folder)
    {

    }

    public function generateFilePath($identifier, $folder)
    {
        $files = count(Storage::allFiles($folder));
        $foldersTree = [];
        $foldersTree[] = intval($files/1000);
        $foldersTree[] = $files/100;
    }
}

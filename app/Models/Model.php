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
       $prefix = $this->generateFilePath($folder);
       $path = $this->getFilePath($prefix, $folder);

       list($type, $format) = explode('/', $file->getMimeType());
       $fileName = sprintf('%s-%s.%s',
           $prefix,
           $identifier,
           $format
       );
        Storage::putFileAs($path, $file, $fileName);

       return $fileName;
    }

    public function deleteFile($fileName)
    {
        Storage::delete($fileName);
    }

    public function getFilePath($fileName, $folder)
    {
        if (strpos($fileName, '.') !== false) {
            $fileNameParts = explode('.', $fileName);
            $fileName = array_shift($fileNameParts);
        }
        if (strpos($fileName, '-') !== false) {
            $fileNameParts = explode('-', $fileName);
            $fileName = array_shift($fileNameParts);
        }
        $pathParts = str_split($fileName, 3);

        return sprintf(
            "%s/%s/%s",
            $folder,
            $pathParts[0],
            $pathParts[1]
        );

    }

    public function generateFilePath($folder)
    {
        $files = count(Storage::allFiles($folder));
        $fileNameCounter = strval($files + 1);
        $nameLength = config('filesystems')['avatars']['name']['length'];
        $charsLeft = $nameLength - strlen($fileNameCounter);
        $fileName = '';

        for($charsCounter = 0; $charsCounter < $charsLeft; $charsCounter++){
            $fileName .= '0';
        }
        $fileName .= $fileNameCounter;

        return $fileName;
    }

    public function getFilePublicUrl($path, $folder)
    {
        return !empty($path) ?
            asset(sprintf(
                'storage/%s/%s',
                $this->getFilePath($path,  $folder),
                $path
            )) :
            null;
    }
}

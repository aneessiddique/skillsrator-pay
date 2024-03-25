<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait StorageMedia {

    public function upload_linode($file, $path, $key=0)
    {
        if($file) {

            // $fileName   = time() . '_' . $file->getClientOriginalName();
            $fileName   = date('Ymd')."_".time().$key.".".$file->getClientOriginalExtension();
            $path = $path . '/' . $fileName;
            Storage::disk('linode')->put($path, file_get_contents($file), 'public');
            $file_name  = $fileName; //$file->getClientOriginalName();
            $file_type  = $file->getClientOriginalExtension();
            $filePath   = env('LINODE_URL') . '/' . $path;

            return $file = [
                'fileName' => $file_name,
                'fileType' => $file_type,
                'filePath' => $filePath,
                'fileSize' => $this->fileSize($file)
            ];
        }
    }

    public function fileSize($file, $precision = 2)
    {   
        $size = $file->getSize();

        if ( $size > 0 ) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}

?>
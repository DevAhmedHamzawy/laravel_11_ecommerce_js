<?php

namespace App\Upload;

use Illuminate\Support\Str;

class Upload
{
    public static function uploadImage($image, $sectionName, $name){

        $fileName = Str::slug($name) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->storePubliclyAs("public/{$sectionName}/", $fileName);

        return $fileName;
    }

    public static function deleteImage($sectionName,$img)
    {
        $path = "../storage/app/public/public/{$sectionName}/";
        if (file_exists($path . $img)) {

            @unlink($path . $img);

        }
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
trait UplaodImageTraits
{
  public function UploadImage($folder, $image)
    {

        if (preg_match('/^data:image\/(\w+);base64,/', $image)) {

            $image = substr($image, strpos($image, ',') + 1);
            $image = base64_decode($image);


            $filename = Str::random(10) . '.jpg';


            Storage::put("public/{$folder}/{$filename}", $image);

            return $filename;
        } elseif ($image instanceof \Illuminate\Http\UploadedFile) {




        $extension = $image->getClientOriginalExtension();
        $filename = Str::random(10) . '.' . $extension;


        $image->storeAs("public/{$folder}", $filename);
        return $filename;
        } else {
            throw new \Exception('Invalid image data');
        }
    }
}
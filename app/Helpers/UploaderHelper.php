<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

trait UploaderHelper
{
    /**
     * upload file through $request, Compress it.
     * to the server in public folder: /public/images/{categoryNameFolder}.
     * if_not_exist : create it with 775 permission.
     *
     * @param Request $request
     * @return String
     */
    public function uploadImage($imageFromRequest, $imageFolder, $prefx = '', $resize_w = 0, $resize_h = 0, $thumb = false)
    {
        if (!file_exists(public_path('uploads/' . $imageFolder))) {
            mkdir(public_path('uploads/' . $imageFolder), 0777, true);
            if ($thumb == true) mkdir(public_path('uploads/' . $imageFolder . '/thumb'), 0777, true);
        }

        $prefx = ($prefx != '') ? $prefx . '_' : '';
        $fileName = $prefx . time() . '.' . $imageFromRequest->getClientOriginalExtension();
        $location = public_path('uploads/' . $imageFolder . '/' . $fileName);

        $image = Image::make($imageFromRequest);

        if ($resize_w > 0 && $resize_h > 0) {
            $image->resize($resize_w, $resize_h, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $image->save($location, 70);

        # Optional Resize.
        if ($thumb == true) {
            $image->thumb(100, 100);
            $newlocation = public_path('uploads/' . $imageFolder . '/thumb' . '/' . $fileName);
            $image->save($newlocation, 70);
        }

        return $fileName;
    }

    public function uploadFile($fileFromRequest, $fileFolder, $prefx = '')
    {

        $prefx = ($prefx != '') ? $prefx . '_' : '';
        $fileName = $prefx . time() . '.' . $fileFromRequest->getClientOriginalExtension();
        
        $location = public_path('uploads/' . $fileFolder . '/');
        $fileFromRequest->move($location, $fileName);

        return $fileName;
    }

    /**
     * Call uploadImage() func to upload photo album.
     *
     * @param [type] $photos
     * @return void
     */
    public function uploadAlbum($photos, $folder)
    {
        foreach ($photos as $key => $album) {
            $imageName = $this->uploadImage($album, $folder);
            $photos[$key] = $imageName;
        }
        return $photos;
    }
}

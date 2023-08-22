<?php

namespace App\services;

use Intervention\Image\Facades\Image;


class ImageService
{
    public function UpdateImage($model, $request, $path, $methodType=null)
    {
        $image = Image::make($request->file('image'));
        if (!empty($model->image)) {
            $currentImage = public_path() . $path . $model->image;

            if (file_exists($currentImage)) {
                unlink($currentImage);
            }
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $image->crop(
            $request->width,
            $request->height,
            $request->left,
            $request->top
        );
        $name = time() . '.' . $extension;
        $image->save(public_path() . $path . $name);

        $model->image = $name;
        $model->save();
    }
}

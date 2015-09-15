<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class ImageResizeController extends BaseController {

    public function index($display_size, $image_size, $image_path) {

        $pixel_density = 2;

        $display_sizes = [
            'sm' => 320,
            'md' => 480,
            'lg' => 600,
            'xl' => 960
        ];

        $image_sizes = [
            'full'    => 1,
            'half'    => .5,
            'third'   => .34,
            'quarter' => .25
        ];

        $compressions = [
            'sm' => 80,
            'md' => 80,
            'lg' => 80,
            'xl' => 80
        ];

        $src_path = public_path($image_path);
        $new_width = (int)(($display_sizes[$display_size] * $pixel_density) * $image_sizes[$image_size]);
        $compression = $compressions[$display_size];
        $colors = (int)Input::get('colors');

        // Throw 404 if image does not exist
        if (!file_exists($src_path)) {
            return response('image not found', 404);
        }

        $img = Image::make($src_path);

        $img->resize($new_width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        if ($colors > 0) {
            $img->limitColors($colors, '#ffffff');
        }

        $response = Response::make($img->encode(null, $compression), 200);

        return $this->addHeaders($response, $img);

    }

    private function addHeaders($response, $img) {
        $response->header('Content-Type', $img->mime());
        $response->header('Cache-Control', 'max-age=31536000');

        return $response;
    }

}
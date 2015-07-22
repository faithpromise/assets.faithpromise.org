<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Response;
use Intervention\Image\ImageManagerStatic as Image;

function add_headers($response, $img) {
    $response->header('Content-Type', $img->mime());
    $response->header('Cache-Control', 'max-age=31536000');
    return $response;
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/series-fade-out/{series_ident}.{ext}', function($series_ident, $ext) {

    $src_path = public_path('images/series/' . $series_ident . '-tall.' . $ext);
    $fill_image_path = public_path('images/overlays/hero-video-overlay.png');

    $img = Image::make($src_path)->resize(720, 405)->fill($fill_image_path);

    return add_headers($img->response(null, 80), $img);

})->where('ext', '(jpg|png|gif)');

Route::get('/{display_size}/{image_size}/{image_path}', function ($display_size, $image_size, $image_path) {

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

    // Throw 404 if image does not exist
    if (! file_exists($src_path)) {
        return response('image not found', 404);
    }

    $img = Image::make($src_path)->resize($new_width, null, function($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

    $response = new Response($img->encode(null, $compression), 200);

    return add_headers($response, $img);

})->where('display_size', '(sm|md|lg|xl)')->where('image_size', '(full|half|third|quarter)')->where('image_path', '.*');

<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as HttpClient;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class EightBitController extends BaseController {

    public function index(Request $request) {

        $css = [];
        $staff = $this->getEightBitData();
        $size = $request->input('size', 'desktop');
        $icon_width = $this->getIconSize($size);
        $max_age_days = 30;

        for ($i = 0; $i < count($staff); ++$i) {

            $path = public_path('images/staff/' . $staff[$i] . '-8bit-square.jpg');
            $staff_image = Image::make($path)->resize($icon_width, $icon_width);
            $css[] = '.Staff8bit-' . $staff[$i] . ' { background-image:url(' . $staff_image->encode('data-url')->getEncoded() . '); }';
        }

        $response = Response::make(implode("\n", $css));
        $response->header('Cache-Control', 'max-age=' . ($max_age_days * 24 * 60 * 60) . ', public');
        $response->header('Content-Type', 'text/css');

        return $response;

    }

    private function getEightBitData() {
        $response = (new HttpClient())->get('http://faithpromise.org/staff/8bit.json');

        return json_decode($response->getBody(true));
    }

    private function getIconSize($size) {
        $sizes = [
            'mobile'  => 45,
            'tablet'  => 70,
            'desktop' => 120
        ];

        return $sizes[$size];
    }

}

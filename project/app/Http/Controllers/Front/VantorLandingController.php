<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Models\Product;

/**
 * Homepage: the Vantor eCommerce Pro sales page. "Buy" goes to the digital checkout of the
 * vantor-ecommerce-pro product once it is set up as a digital product, otherwise to WhatsApp.
 */
class VantorLandingController extends Controller
{
    public function __invoke()
    {
        $gs = GeneralSettings::first();
        $product = Product::where('slug', 'vantor-ecommerce-pro')->where('status', 1)->first();
        $price = (float) ($product->price ?? 3999);
        $toBn = fn (string $s) => strtr($s, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);

        $digits = preg_replace('/\D/', '', (string) ($gs->phone ?? ''));
        if (str_starts_with($digits, '01')) { $digits = '88' . $digits; }
        $waNumber = strlen($digits) >= 12 ? $digits : '8801775075543';
        $local = substr($waNumber, 2);
        $waBase = 'https://wa.me/' . $waNumber;
        $priceBn = '৳' . $toBn(number_format($price, 0));

        $canBuy = $product && $product->is_digital && $product->google_drive_file_id;
        $buyUrl = $canBuy
            ? route('digital-product.checkout', $product)
            : $waBase . '?text=' . rawurlencode("আসসালামু আলাইকুম Jukti Tech! আমি Vantor eCommerce Pro সোর্স কোড কিনতে চাই।");

        return view('frontend.vantor', [
            'buyUrl'    => $buyUrl,
            'waBase'    => $waBase,
            'waDisplay' => $toBn(substr($local, 0, 5) . '-' . substr($local, 5)),
            'priceBn'   => $priceBn,
            'assetBase' => asset('assets/vantor'),
        ]);
    }
}

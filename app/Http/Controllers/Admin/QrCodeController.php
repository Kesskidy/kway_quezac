<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Contracts\View\View;

class QrCodeController extends Controller
{
    /** Page de démonstration : un QR code par code de bouteille, à imprimer ou à scanner. */
    public function index(): View
    {
        $qrcode = new QRCode(new QROptions(['outputBase64' => true]));

        $codes = PromoCode::orderBy('id')->get()->map(function (PromoCode $promoCode) use ($qrcode) {
            // Le QR mène au jeu avec le code déjà saisi : /?code=QZK-XXXX-XXXX#game
            $url = route('landing', ['code' => $promoCode->code]).'#game';

            return [
                'code' => $promoCode->code,
                'url' => $url,
                'used' => $promoCode->used_at !== null,
                'image' => $qrcode->render($url),
            ];
        });

        return view('admin.qr-codes', ['codes' => $codes]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFactory;

class LandingController extends Controller
{
    public function index(): View
    {
        // regle.md names the view `landing`; use `app` until the front renames app.blade.php.
        return view(ViewFactory::exists('landing') ? 'landing' : 'app', ['products' => Product::all()]);
    }
}

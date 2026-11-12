<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFactory;

class LandingController extends Controller
{
    /**
     * GET / — the landing page receives $products (regle.md, section 3).
     */
    public function index(): View
    {
        // The contract names the view `landing`. Until the front renames app.blade.php,
        // fall back to `app` so the home page keeps working.
        $view = ViewFactory::exists('landing') ? 'landing' : 'app';

        return view($view, [
            'products' => Product::orderBy('id')->get(),
        ]);
    }
}

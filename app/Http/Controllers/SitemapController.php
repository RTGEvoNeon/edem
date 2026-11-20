<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WholesaleProduct;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = WholesaleProduct::available()->orderBy('id')->get();
        $pages = [url('/'), route('contacts'), route('privacy'), route('oferta')];
        foreach ($products as $product) {
            $pages[] = route('wholesale.show', $product->slug);
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($pages as $page) {
            $xml .= '  <url><loc>'.htmlspecialchars($page, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc></url>'."\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}

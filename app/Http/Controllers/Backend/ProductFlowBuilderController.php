<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class ProductFlowBuilderController extends Controller
{
    /**
     * Display the Product Flow Visual Builder page
     */
    public function index()
    {
        $frontendUrl = config('app.frontend_url', env('FRONTEND_URL', 'https://chandra-asri.com'));
        $secret = config('app.flow_builder_secret', env('FLOW_BUILDER_SECRET', 'cap25-product-flow-secret-2026'));
        $editorUrl = $frontendUrl . '/en/admin/flow-editor?secret=' . urlencode($secret);
        $option = Option::where('option_name', 'product_flow_coordinates')->first();
        $hasCustomLayout = !empty($option && $option->option_value && $option->option_value !== '{}');

        return view('backend.product-flow.index', compact('editorUrl', 'hasCustomLayout'));
    }
}


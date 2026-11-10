<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class ProductFlowController extends Controller
{
    /**
     * Get saved coordinates and customized node texts for product flow diagram
     */
    public function getLayout()
    {
        try {
            $coordsOption = Option::where('option_name', 'product_flow_coordinates')->first();
            $nodesOption = Option::where('option_name', 'product_flow_nodes')->first();

            $coordinates = $coordsOption && $coordsOption->option_value ? json_decode($coordsOption->option_value, true) : null;
            $nodes = $nodesOption && $nodesOption->option_value ? json_decode($nodesOption->option_value, true) : null;

            return response()->json([
                'status' => true,
                'data' => [
                    'coordinates' => $coordinates ?: new \stdClass(),
                    'nodes' => $nodes ?: new \stdClass()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save updated coordinates and customized node texts from visual flow builder
     */
    public function saveLayout(Request $request)
    {
        try {
            $coordinates = $request->input('coordinates');
            $nodes = $request->input('nodes');

            if ($coordinates && is_array($coordinates)) {
                Option::updateOrCreate(
                    ['option_name' => 'product_flow_coordinates'],
                    [
                        'option_value' => json_encode($coordinates),
                        'is_autoload' => 1
                    ]
                );
            }

            if ($nodes && is_array($nodes)) {
                Option::updateOrCreate(
                    ['option_name' => 'product_flow_nodes'],
                    [
                        'option_value' => json_encode($nodes),
                        'is_autoload' => 1
                    ]
                );
            }

            Option::refreshCache();

            return response()->json([
                'status' => true,
                'message' => 'Product flow layout and node details successfully saved.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}


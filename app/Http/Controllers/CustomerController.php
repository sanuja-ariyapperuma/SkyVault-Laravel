<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CustomerController extends BaseController
{
    use ValidatesRequests;

    public function __construct(
        private CustomerService $customerService
    ) {}

    public function search(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string|min:2|max:100'
        ]);

        try {
            $results = $this->customerService->search($validated['term']);

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Search completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }
}

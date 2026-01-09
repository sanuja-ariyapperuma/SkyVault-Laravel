<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\MetaDataService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class CustomerController extends BaseController
{
    use ValidatesRequests;

    public function __construct(
        private CustomerService $customerService,
        private MetaDataService $metaService
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

    public function show(string $customerid): View
    {
        
        if (empty($customerid) || !Str::isUuid($customerid)) {
            abort(400, 'Invalid customer ID format');
        }

        $salutations = $this->metaService->salutations();
        $customerData = $this->customerService->getCustomerShowData($customerid, $salutations);

        if (is_null($customerData)) {
            abort(404, 'Customer not found');
        }

        return view('customer.show', $customerData);
    }
}

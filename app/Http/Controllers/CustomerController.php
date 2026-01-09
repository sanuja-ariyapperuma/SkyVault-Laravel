<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\MetaDataService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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

    public function show($customerId){
        try {
            $salutations = $this->metaService->salutations();
            $customer = $this->customerService->customerDetails($customerId);

            //dd($customer->phones()->first()->phone_number);

            return view('customer.show', [
                'customerId' => $customerId,
                'salutations' => $salutations,
                'customer_first_name' => $customer->first_name,
                'customer_last_name' => $customer->last_name,
                'customer_salutation' => $customer->salutation,
                'customer_assigned_user' => $customer->user ? $customer->user->first_name . " " . $customer->user->last_name : null,
                'customer_email' => $customer->emails()->first()?->email,
                'customer_phone' => $customer->phones()->first()?->phone_number,
                'customer_address' => $customer->addresses()->first(),
                'customer_communication_method' => $customer->communication_method,
                'customer_salutations' => $customer->salutation,
                'customer_staff_member' => $customer->user ? $customer->user->first_name . " " . $customer->user->last_name : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bad request: ' . $e->getMessage()
            ], 400);
        }
    }
}

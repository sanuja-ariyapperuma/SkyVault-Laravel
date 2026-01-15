<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\MetaDataService;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

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

    public function show(MetaDataService $metaService, string $customerId): View
    {
        
        if (empty($customerId) || !Str::isUuid($customerId)) {
            abort(400, 'Invalid customer ID format');
        }

        $salutations = $metaService->salutations();
        $customerData = $this->customerService->getCustomerShowData($customerId, $salutations);

        if (is_null($customerData)) {
            abort(404, 'Customer not found');
        }

        return view('customer.show', $customerData);
    }

    public function phoneModal(string $customerId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        try {
            $customerData = $this->customerService->getCustomerShowData($customerId, []);
            
            if (is_null($customerData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $modalHtml = view('customer.modals.phone-modal', $customerData)->render();

            return response()->json([
                'success' => true,
                'html' => $modalHtml,
                'data' => $customerData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load phone modal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storePhone(StorePhoneRequest $request, string $customerId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        try {
            $phoneData = $request->validated();
            $phone = $this->customerService->addPhone($customerId, $phoneData);

            return response()->json([
                'success' => true,
                'message' => 'Phone number added successfully',
                'data' => [
                    'id' => $phone->id,
                    'number' => $phone->phone_number,
                    'isDefault' => $phone->is_default,
                    'isWhatsapp' => $phone->is_whatsapp
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add phone number: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeEmail(StoreEmailRequest $request, string $customerId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        try {
            $emailData = $request->validated();
            $email = $this->customerService->addEmail($customerId, $emailData);

            return response()->json([
                'success' => true,
                'message' => 'Email address added successfully',
                'data' => [
                    'id' => $email->id,
                    'email' => $email->email
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add email address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePhone(UpdatePhoneRequest $request, string $customerId, string $phoneId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        if (empty($phoneId) || !Str::isUuid($phoneId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone ID format'
            ], 400);
        }

        try {
            $phoneData = $request->validated();
            $phone = $this->customerService->updatePhone($customerId, $phoneId, $phoneData);

            return response()->json([
                'success' => true,
                'message' => 'Phone number updated successfully',
                'data' => [
                    'id' => $phone->id,
                    'number' => $phone->phone_number,
                    'isDefault' => $phone->is_default,
                    'isWhatsapp' => $phone->is_whatsapp
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update phone number: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeAddress(StoreAddressRequest $request, string $customerId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        try {
            $addressData = $request->validated();
            $address = $this->customerService->addAddress($customerId, $addressData);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'data' => [
                    'id' => $address->id,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country_id' => $address->country_id,
                    'country_name' => $address->country->name ?? null,
                    'isDefault' => $address->is_default ?? false
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAddress(UpdateAddressRequest $request, string $customerId, string $addressId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        if (empty($addressId) || !Str::isUuid($addressId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid address ID format'
            ], 400);
        }

        try {
            $addressData = $request->validated();
            $address = $this->customerService->updateAddress($customerId, $addressId, $addressData);

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => [
                    'id' => $address->id,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country_id' => $address->country_id,
                    'country_name' => $address->country->name ?? null,
                    'isDefault' => $address->is_default ?? false
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteEmail(string $customerId, string $emailId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        if (empty($emailId) || !Str::isUuid($emailId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email ID format'
            ], 400);
        }

        try {
            $this->customerService->deleteEmail($customerId, $emailId);

            return response()->json([
                'success' => true,
                'message' => 'Email address deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete email address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePhone(string $customerId, string $phoneId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        try {
            $this->customerService->deletePhone($customerId, $phoneId);

            return response()->json([
                'success' => true,
                'message' => 'Phone number deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete phone number: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setDefaultPhone(string $customerId, string $phoneId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        if (empty($phoneId) || !Str::isUuid($phoneId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone ID format'
            ], 400);
        }

        try {
            $this->customerService->setDefaultPhone($customerId, $phoneId);

            return response()->json([
                'success' => true,
                'message' => 'Default phone number updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update default phone number: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setDefaultEmail(string $customerId, string $emailId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        if (empty($emailId) || !Str::isUuid($emailId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email ID format'
            ], 400);
        }

        try {
            $this->customerService->setDefaultEmail($customerId, $emailId);

            return response()->json([
                'success' => true,
                'message' => 'Default email address updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update default email address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function emailModal(string $customerId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        try {
            $customerData = $this->customerService->getCustomerShowData($customerId, []);
            
            if (is_null($customerData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $modalHtml = view('customer.modals.email-modal', $customerData)->render();

            return response()->json([
                'success' => true,
                'html' => $modalHtml,
                'data' => $customerData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load email modal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addressModal(string $customerId): JsonResponse
    {
        if (empty($customerId) || !Str::isUuid($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID format'
            ], 400);
        }

        try {
            $customerData = $this->customerService->getCustomerShowData($customerId, []);
            
            if (is_null($customerData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $modalHtml = view('customer.modals.address-modal', $customerData)->render();

            return response()->json([
                'success' => true,
                'html' => $modalHtml,
                'data' => $customerData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load address modal: ' . $e->getMessage()
            ], 500);
        }
    }
}

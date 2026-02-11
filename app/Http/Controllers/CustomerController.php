<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ApiResponseTrait;
use App\Services\CustomerService;
use App\Services\MetaDataService;
use App\Http\Requests\CustomerAddressUuidRequest;
use App\Http\Requests\CustomerEmailUuidRequest;
use App\Http\Requests\CustomerPhoneUuidRequest;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Requests\SearchCustomerRequest;
use App\Http\Requests\CustomerUuidRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class CustomerController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(
        private CustomerService $customerService
    ) {}


    private function handleStoreOperation(
        string $customerId,
        $request,
        string $serviceMethod,
        string $successMessage,
        array $responseFields,
        string $entityName
    ): JsonResponse {

        try {
            $data = $request->validated();
            $entity = $this->customerService->$serviceMethod($customerId, $data);

            $responseData = [];
            foreach ($responseFields as $field) {
                if (str_contains($field, '.')) {
                    $parts = explode('.', $field);
                    $responseData[$parts[1]] = $entity->{$parts[0]}->{$parts[1]} ?? null;
                } else {
                    $responseData[$field] = $entity->$field;
                }
            }

            return $this->successResponse($successMessage, $responseData);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to add ' . $entityName . ': ' . $e->getMessage(), 500);
        }
    }

    private function handleUpdateOperation(
        string $customerId,
        string $entityId,
        $request,
        string $serviceMethod,
        string $successMessage,
        array $responseFields,
        string $entityName
    ): JsonResponse {

        try {
            $data = $request->validated();
            $entity = $this->customerService->$serviceMethod($customerId, $entityId, $data);

            $responseData = [];
            foreach ($responseFields as $field) {
                if (str_contains($field, '.')) {
                    $parts = explode('.', $field);
                    $responseData[$parts[1]] = $entity->{$parts[0]}->{$parts[1]} ?? null;
                } else {
                    $responseData[$field] = $entity->$field;
                }
            }

            return $this->successResponse($successMessage, $responseData);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update ' . $entityName . ': ' . $e->getMessage(), 500);
        }
    }

    private function handleDeleteOperation(
        string $customerId,
        string $entityId,
        string $serviceMethod,
        string $successMessage,
        string $entityName
    ): JsonResponse {

        try {
            $this->customerService->$serviceMethod($customerId, $entityId);
            return $this->successResponse($successMessage);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete ' . $entityName . ': ' . $e->getMessage(), 500);
        }
    }

    private function handleSetDefaultOperation(
        string $customerId,
        string $entityId,
        string $serviceMethod,
        string $successMessage,
        string $entityName
    ): JsonResponse {

        try {
            $this->customerService->$serviceMethod($customerId, $entityId);
            return $this->successResponse($successMessage);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update default ' . $entityName . ': ' . $e->getMessage(), 500);
        }
    }

    public function search(SearchCustomerRequest $request)
    {
        $validated = $request->validated();

        try {
            $results = $this->customerService->search($validated['term']);

            return $this->successResponse('Search completed successfully', $results);
        } catch (\Exception $e) {
            return $this->errorResponse('Search failed: ' . $e->getMessage(), 500);
        }
    }

    public function show(CustomerUuidRequest $request, MetaDataService $metaService, string $customerId): View
    {

        $salutations = $metaService->salutations();
        $customerData = $this->customerService->getCustomerShowData($customerId, $salutations);

        if (is_null($customerData)) {
            abort(404, 'Customer not found');
        }

        return view('customer.show', $customerData);
    }

    private function loadModalHtml(CustomerUuidRequest $request, string $customerId, string $modalType, string $viewPath, string $dataKey): JsonResponse
    {

        try {
            $customerData = $this->customerService->getCustomerShowData($customerId, []);
            
            if (is_null($customerData)) {
                return $this->errorResponse('Customer not found', 404);
            }

            $modalHtml = view($viewPath, $customerData)->render();

            return $this->successResponse('', ['html' => $modalHtml, $dataKey => $customerData[$dataKey] ?? []]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to load ' . $modalType . ' modal: ' . $e->getMessage(), 500);
        }
    }

    public function phoneModal(CustomerUuidRequest $request, string $customerId): JsonResponse
    {
        return $this->loadModalHtml($request, $customerId, 'phone', 'customer.modals.phone-modal', 'phones');
    }

    public function storePhone(StorePhoneRequest $request, CustomerUuidRequest $uuidRequest, string $customerId): JsonResponse
    {
        return $this->handleStoreOperation(
            $customerId,
            $request,
            'addPhone',
            'Phone number added successfully',
            ['id', 'number' => 'phone_number', 'isDefault' => 'is_default', 'isWhatsapp' => 'is_whatsapp'],
            'phone number'
        );
    }

    public function storeEmail(StoreEmailRequest $request, CustomerUuidRequest $uuidRequest, string $customerId): JsonResponse
    {
        return $this->handleStoreOperation(
            $customerId,
            $request,
            'addEmail',
            'Email address added successfully',
            ['id', 'email'],
            'email address'
        );
    }

    public function updateEmail(UpdateEmailRequest $request, CustomerEmailUuidRequest $uuidRequest, string $customerId, string $emailId): JsonResponse
    {
        return $this->handleUpdateOperation(
            $customerId,
            $emailId,
            $request,
            'updateEmail',
            'Email address updated successfully',
            ['id', 'email'],
            'email address'
        );
    }

    public function updatePhone(UpdatePhoneRequest $request, CustomerPhoneUuidRequest $uuidRequest, string $customerId, string $phoneId): JsonResponse
    {
        return $this->handleUpdateOperation(
            $customerId,
            $phoneId,
            $request,
            'updatePhone',
            'Phone number updated successfully',
            ['id', 'number' => 'phone_number', 'isDefault' => 'is_default', 'isWhatsapp' => 'is_whatsapp'],
            'phone'
        );
    }

    public function storeAddress(StoreAddressRequest $request, CustomerUuidRequest $uuidRequest, string $customerId): JsonResponse
    {
        return $this->handleStoreOperation(
            $customerId,
            $request,
            'addAddress',
            'Address added successfully',
            [
                'id',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'postal_code',
                'country_id',
                'country_name' => 'country.name',
                'isDefault' => 'is_default'
            ],
            'address'
        );
    }

    public function updateAddress(UpdateAddressRequest $request, CustomerAddressUuidRequest $uuidRequest, string $customerId, string $addressId): JsonResponse
    {
        return $this->handleUpdateOperation(
            $customerId,
            $addressId,
            $request,
            'updateAddress',
            'Address updated successfully',
            [
                'id',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'postal_code',
                'country_id',
                'country_name' => 'country.name',
                'isDefault' => 'is_default'
            ],
            'address'
        );
    }

    public function deleteEmail(CustomerEmailUuidRequest $request, string $customerId, string $emailId): JsonResponse
    {
        return $this->handleDeleteOperation(
            $customerId,
            $emailId,
            'deleteEmail',
            'Email address deleted successfully',
            'email address'
        );
    }

    public function deletePhone(CustomerPhoneUuidRequest $request, string $customerId, string $phoneId): JsonResponse
    {
        return $this->handleDeleteOperation(
            $customerId,
            $phoneId,
            'deletePhone',
            'Phone number deleted successfully',
            'phone number'
        );
    }

    public function deleteAddress(CustomerAddressUuidRequest $request, string $customerId, string $addressId): JsonResponse
    {
        return $this->handleDeleteOperation(
            $customerId,
            $addressId,
            'deleteAddress',
            'Address deleted successfully',
            'address'
        );
    }

    public function setDefaultPhone(CustomerPhoneUuidRequest $request, string $customerId, string $phoneId): JsonResponse
    {
        return $this->handleSetDefaultOperation(
            $customerId,
            $phoneId,
            'setDefaultPhone',
            'Default phone number updated successfully',
            'phone number'
        );
    }

    public function setDefaultEmail(CustomerEmailUuidRequest $request, string $customerId, string $emailId): JsonResponse
    {
        return $this->handleSetDefaultOperation(
            $customerId,
            $emailId,
            'setDefaultEmail',
            'Default email address updated successfully',
            'email address'
        );
    }

    public function setDefaultAddress(CustomerAddressUuidRequest $request, string $customerId, string $addressId): JsonResponse
    {
        return $this->handleSetDefaultOperation(
            $customerId,
            $addressId,
            'setDefaultAddress',
            'Primary address updated successfully',
            'address'
        );
    }

    public function emailModal(CustomerUuidRequest $request, string $customerId): JsonResponse
    {
        return $this->loadModalHtml($request, $customerId, 'email', 'customer.modals.email-modal', 'emails');
    }

    public function addressModal(CustomerUuidRequest $request, string $customerId, MetaDataService $metaService): JsonResponse
    {
        try {
            $customerData = $this->customerService->getCustomerShowData($customerId, []);
            
            if (is_null($customerData)) {
                return $this->errorResponse('Customer not found', 404);
            }

            // Add countries to the customer data
            $customerData['countries'] = $metaService->countries();

            $modalHtml = view('customer.modals.address-modal', $customerData)->render();

            return $this->successResponse('', [
                'html' => $modalHtml, 
                'addresses' => $customerData['addresses'] ?? [],
                'countries' => $customerData['countries']
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to load address modal: ' . $e->getMessage(), 500);
        }
    }

    public function passportModal(CustomerUuidRequest $request, string $customerId): JsonResponse
    {
        return $this->loadModalHtml($request, $customerId, 'passport', 'customer.modals.passport-modal', 'passports');
    }

    public function visaModal(CustomerUuidRequest $request, string $customerId): JsonResponse
    {
        return $this->loadModalHtml($request, $customerId, 'visa', 'customer.modals.visa-modal', 'visas');
    }

    public function frequentFlyerModal(CustomerUuidRequest $request, string $customerId): JsonResponse
    {
        return $this->loadModalHtml($request, $customerId, 'frequent-flyer', 'customer.modals.frequent-flyer-modal', 'frequentFlyers');
    }
}

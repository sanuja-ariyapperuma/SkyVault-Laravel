<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function search(string $term, int $limit = 5): EloquentCollection
    {
        $term = trim($term);
        if (empty($term)) {
            return new EloquentCollection();
        }
        
        // Escape LIKE wildcards to ensure literal matching
        $searchTerm = '%' . addcslashes($term, '%_\\') . '%';
        
        return Customer::query()
            ->where(function ($query) use ($searchTerm, $term) {
                // Single word: search in first_name and last_name
                if (!str_contains($term, ' ')) {
                    $query->where('first_name', 'like', $searchTerm)
                          ->orWhere('last_name', 'like', $searchTerm);
                } else {
                    // Multi-word: split and search for ALL words (AND logic)
                    $words = array_filter(explode(' ', $term), 'trim');
                    if (!empty($words)) {
                        foreach ($words as $word) {
                            $wordSearch = '%' . addcslashes($word, '%_\\') . '%';
                            $query->where(function ($subQuery) use ($wordSearch) {
                                $subQuery->where('first_name', 'like', $wordSearch)
                                        ->orWhere('last_name', 'like', $wordSearch);
                            });
                        }
                    }
                }
                
                // Search in related models (only if term is reasonable length)
                if (strlen($term) >= 2) {
                    $query->orWhereHas('emails', fn($q) => $q->where('email', 'like', $searchTerm))
                          ->orWhereHas('phones', fn($q) => $q->where('phone_number', 'like', $searchTerm))
                          ->orWhereHas('passports', fn($q) => $q
                              ->where('passport_number', 'like', $searchTerm)
                              ->orWhere('first_name', 'like', $searchTerm)
                              ->orWhere('other_names', 'like', $searchTerm)
                          );
                }
            })
            ->with(['emails', 'phones', 'passports'])
            ->select(['id', 'first_name', 'last_name', 'salutation'])
            ->orderBy('last_name')
            ->limit($limit)
            ->get();
    }

    public function customerDetails(string $customerId) : ?Customer
    {
        return Customer::query()
            ->with([
                'user' => fn($query) => $query->select(['id', 'first_name', 'last_name']),
                'addresses' => fn($query) => $query->where('is_default', true)->with('country'),
                'phones' => fn($query) => $query->where('is_default', true)->select('phone_number'),
                'emails' => fn($query) => $query->where('is_default', true)->select('email')
            ])
            ->find($customerId);
    }

}
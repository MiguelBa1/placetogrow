<?php

namespace App\Http\Resources\Subscription;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name . ' ' . $this->last_name,
            'document_type' => __('document_types.' . $this->document_type->value),
            'document_number' => $this->document_number,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}

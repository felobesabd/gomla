<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $supplierId = $this->route('supplier');
        return [
            'company_name' => 'required|string',
            'phone_number' => 'required|string|unique:suppliers,phone_number,' . $supplierId,
            'address' => 'required|string',
            'commercial_register_no' => 'required|string',
            'tax_value_added' => 'required|string',
            'supplier_name' => 'required|array',
            'supplier_name.*' => 'required|string',
            'email' => 'required|array',
            'email.*' => [
                'required',
                'email',
                Rule::unique('supplier_contacts', 'email')->ignore($supplierId, 'supplier_id'),
            ],
            'phone' => 'required|array',
            'phone.*' => [
                'required',
                'string',
                Rule::unique('supplier_contacts', 'phone')->ignore($supplierId, 'supplier_id'),
            ],
            'whats_app' => 'nullable|array',
            'whats_app.*' => 'nullable|string|unique:supplier_contacts,phone,' . $supplierId . ',supplier_id',
            'department' => 'nullable|array',
            'department.*' => 'nullable|string',

            'supplier_name_add' => 'nullable|array',
            'supplier_name_add.*' => 'nullable|string',
            'email_add' => 'nullable|array',
            'email_add.*' => [
                'nullable',
                'email',
                Rule::unique('supplier_contacts', 'email')->ignore($supplierId, 'supplier_id'),
            ],
            'phone_add' => 'nullable|array',
            'phone_add.*' => [
                'nullable',
                'string',
                Rule::unique('supplier_contacts', 'phone')->ignore($supplierId, 'supplier_id'),
            ],
            'whats_app_add' => 'nullable|array',
            'whats_app_add.*' => 'nullable|string|unique:supplier_contacts,phone,' . $supplierId . ',supplier_id',
            'department_add' => 'nullable|array',
            'department_add.*' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'supplier_name.*.required' => 'Each supplier must have a name.',
            'email.*.required' => 'Each supplier must have a valid email address.',
            'email.*.unique' => 'The email :input is already exists with another supplier.',
            'email_add.*.unique' => 'The email :input is already exists with another supplier.',
            'whats_app.*.unique' => 'The WhatsApp number :input is already exists with another contact.',
            'whats_app_add.*.unique' => 'The WhatsApp number :input is already exists with another contact.',
            'phone.*.required' => 'Each supplier must have a phone number.',
            'phone.*.unique' => 'The phone number :input is already exists with another supplier.',
            'phone_add.*.unique' => 'The phone number :input is already exists with another supplier.',
        ];
    }
}

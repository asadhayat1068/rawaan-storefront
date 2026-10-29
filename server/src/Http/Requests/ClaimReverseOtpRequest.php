<?php

namespace Fleetbase\Storefront\Http\Requests;

use Fleetbase\Http\Requests\FleetbaseRequest;
use Illuminate\Validation\Rule;

class ClaimReverseOtpRequest extends FleetbaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return session('storefront_key') || request()->session()->has('api_credential');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'challenge_id' => 'required|string',
            'secret'       => 'required|string',
            'mode'         => 'nullable|in:login,signup',
            'name'         => 'required_if:mode,signup',
            'email'        => [
                'email', 'nullable', Rule::unique('contacts')->where(function ($query) {
                    $query->where('company_uuid', session('company'));

                    return $query->whereNull('deleted_at');
                }),
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RpsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // "title" => "required|max:100",
            // "author" => "required|max:100",
            // "isbn" => "required|max:100",
            // "tahun" => "required|max:4",
            // "kategori" => "max:15",
        ];
    }
}
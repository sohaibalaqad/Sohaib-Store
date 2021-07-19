<?php

namespace App\Http\Requests;

use App\Rules\Filter;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:categories,id,' . $id,
                function ($attribute, $value, $fail) {
                    if (stripos($value, 'god') !== false) {
                        $fail('you cannot use god word in your input');
                    }
                },
            ],
            'parent_id' => 'nullable|int|exists:categories,id',
            'description' => [
                'nullable',
                'min:5',
                'filter:html,python'
                // new Filter(['php', 'laravel', 'css']),   # Recommended 
            ],
            'status' => 'required|in:active,draft',
            'image' => 'image|max:512000|dimensions:min_width=300,min_height=300',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'حقل :attribute مطلوب',
            // 'name.required' => 'Category name is required!',
        ];
    }
}

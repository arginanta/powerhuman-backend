<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    // authorize() adalah metode khusus dalam formulir request Laravel yang digunakan untuk menentukan apakah pengguna memiliki izin untuk melakukan tindakan yang diminta.
    public function authorize()
    {
        // Auth::check() digunakan untuk memeriksa apakah pengguna saat ini terautentikasi (login). Metode ini mengembalikan true jika pengguna terautentikasi, dan sebaliknya.
        // Jika Auth::check() mengembalikan true, formulir request dianggap diotorisasi, dan tindakan formulir tersebut dapat dilanjutkan. Jika mengembalikan false, maka Laravel dapat menolak akses dan memberikan respons yang sesuai.
        return Auth::check(); // true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}

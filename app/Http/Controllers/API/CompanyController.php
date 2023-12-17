<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    //
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        // powerhuman.com/api/company?id=1
        if ($id) {
            $company = Company::with(['users'])->find($id);

            if ($company) {
                return ResponseFormatter::success($company, 'Company Found');
            }

            return ResponseFormatter::error('Company not found', 404);
        }

        // powerhuman.com/api/company
        $companies = Company::with(['users']);
        // Membuat query builder untuk mendapatkan semua perusahaan dengan relasi users. with digunakan untuk memuat relasi terkait.

        // Filtering
        // powerhuman.com/api/company?name=Kunde
        if ($companies) {
            $companies->where('name', 'Like', '%' . $name . '%');
        }
        // Memeriksa apakah query builder berhasil dibuat, dan jika ya, menambahkan kondisi where untuk mencari perusahaan berdasarkan nama yang sesuai filter.

        return ResponseFormatter::success(
            $companies->paginate($limit),
            'Companies Found'
        );
    }
}

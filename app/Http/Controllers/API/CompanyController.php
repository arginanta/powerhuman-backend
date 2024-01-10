<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    // List Company by User
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $companyQuery = Company::with(['users'])->whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        });

        // Get single data
        // powerhuman.com/api/company?id=1
        if ($id) {
            $company = $companyQuery->find($id);

            if ($company) {
                return ResponseFormatter::success($company, 'Company Found');
            }

            return ResponseFormatter::error('Company not found', 404);
        }


        // Get multiple data
        $companies = $companyQuery;

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

    public function create(CreateCompanyRequest $request)
    {

        try {
            // Upload Logo
            // Cek apakah ada file logo yang diunggah dalam request
            if ($request->hasFile('logo')) {
                // Jika ada, simpan file logo dalam direktori public/logos
                $path = $request->file('logo')->store('public/logos');
            }

            // Create Company
            // Buat instance Company dengan menggunakan method create dan isi kolom name dan logo
            $company = Company::create([
                'name' => $request->name,
                'logo' => $path, // Jika ada file logo, gunakan path yang telah disimpan
            ]);

            // Jika company tidak berhasil dibuat, lemparkan Exception
            if (!$company) {
                throw new Exception('Company not created');
            }

            // Attach company to user
            // Mengaitkan company yang baru dibuat dengan user yang saat ini terautentikasi
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            // Load users at company
            // Memuat relasi users pada company
            $company->load('users');

            // Berikan response sukses jika tidak ada masalah
            return ResponseFormatter::success($company, 'Company created');
        } catch (Exception $e) {
            // Tangani exception dengan memberikan response error
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateCompanyRequest $request, $id)
    {
        try {
            // Get Company
            // Temukan perusahaan dengan ID yang diberikan
            $company = Company::find($id); // Company::findOrFail($id); 

            // Check if company exist
            // Periksa apakah perusahaan ditemukan
            if (!$company) {
                throw new Exception('Company not found');
            }

            // Uploud Logo
            if ($request->hasFile('logo')) {
                // Simpan file logo yang diunggah dalam direktori 'public/logos'
                $path = $request->file('logo')->store('public/logos');
            }

            // Update company
            // Perbarui perusahaan dengan jalur logo yang baru
            $company->update([
                'name' => $request->name,
                'logo' => $path
            ]);

            // Kembalikan respons sukses dengan data perusahaan yang diperbarui
            return ResponseFormatter::success($company, 'Company Update');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}

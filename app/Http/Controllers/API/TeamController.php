<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;

class TeamController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $teamQuery = Team::query();

        // Get single data
        // powerhuman.com/api/team?id=1
        if ($id) {
            $team = $teamQuery->find($id);

            if ($team) {
                return ResponseFormatter::success($team, 'Team Found');
            }

            return ResponseFormatter::error('Team not found', 404);
        }

        // Get multiple data
        $teams = $teamQuery->where('company_id', $request->company_id);

        // Filtering
        // powerhuman.com/api/team?name=Kunde
        if ($name) {
            $teams->where('name', 'Like', '%' . $name . '%');
        }
        // Memeriksa apakah query builder berhasil dibuat, dan jika ya, menambahkan kondisi where untuk mencari perusahaan berdasarkan nama yang sesuai filter.

        return ResponseFormatter::success(
            $teams->paginate($limit),
            'Teams Found'
        );
    }

    public function create(CreateTeamRequest $request)
    {
        try {

            // Upload Icon
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icons');
            }

            // Create Team
            $team = Team::create([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            if (!$team) {
                throw new Exception('Team not created');
            };

            return ResponseFormatter::success($team, 'Team created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateTeamRequest $request, $id)
    {
        try {
            // Get Team
            // Temukan perusahaan dengan ID yang diberikan
            $team = Team::find($id); // Team::findOrFail($id); 

            // Check if team exist
            // Periksa apakah perusahaan ditemukan
            if (!$team) {
                throw new Exception('Team not found');
            }

            // Uploud Icon
            if ($request->hasFile('icon')) {
                // Simpan file icon yang diunggah dalam direktori 'public/icons'
                $path = $request->file('icon')->store('public/icons');
            }

            // Update team
            // Perbarui perusahaan dengan jalur logo yang baru
            $team->update([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            // Kembalikan respons sukses dengan data perusahaan yang diperbarui
            return ResponseFormatter::success($team, 'Team Update');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Team
            $team = Team::find($id);

            // TODO: Check if team is owned by user

            // Check if team exist
            if (!$team) {
                throw new Exception('Team not found');
            }

            // Delete team
            $team->delete();

            return ResponseFormatter::success('Team Deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}

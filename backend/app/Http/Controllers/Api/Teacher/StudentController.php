<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Teacher: Manajement Siswa', weight: 4)]
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = Student::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Filter berdasarkan Kelas
        if ($request->has('classId') && $request->classId != '') {
            $query->where('class_room_id', $request->classId);
        }

        $students = $query->with('classroom')->paginate($perPage);

        $students->getCollection()->transform(function ($student) {
            return new StudentResource($student);
        });

        return ApiResponse::success($students, 'Berhasil mengambil data siswa');
    }

    /**
     * Display detail student.
     */
    public function detail(string $id)
    {
        $student = Student::with(['dailyMoods' => function ($query) {
            $query->latest()->take(30);
        }])->find($id);

        if (! $student) {
            return ApiResponse::error('Data siswa tidak ditemukan', 404);
        }

        return ApiResponse::success([
            'name' => $student->name,
            'nisn' => $student->nisn,
            'avatar_url' => $student->avatar_url,
            'phone' => $student->phone_number,
            'address' => $student->address,
            'parent_name' => $student->parent_name,
            'parent_phone' => $student->parent_phone_number,
            'classroom' => $student->classroom->name,
            'mood_history' => $student->dailyMoods->map(fn ($m) => [
                'id' => $m->id,
                'emotion_name' => $m->emotion_name,
                'magnitude' => $m->magnitude,
                'story' => $m->story,
                'formatted_date' => $m->created_at->translatedFormat('d M Y'),
            ]),
            'chart_labels' => $student->dailyMoods->reverse()->pluck('created_at')->map(fn ($d) => $d->format('d M')),
            'chart_data' => $student->dailyMoods->reverse()->pluck('magnitude'),
        ]);
    }
}

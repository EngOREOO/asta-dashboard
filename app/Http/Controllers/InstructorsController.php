<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorsController extends Controller
{
    public function index(Request $request)
    {
        // Optimized query with eager loading and counts to prevent N+1 queries
        $instructors = User::role('instructor')
            ->with(['roles'])
            ->withCount([
                'instructorCourses as courses_count',
                'courses as total_courses_count'
            ])
            ->orderByDesc('id')
            ->paginate(20);

        return view('instructors.index', compact('instructors'));
    }

    public function show(User $instructor)
    {
        // Eager load all necessary relationships in one query
        $instructor->load([
            'roles',
            'instructorCourses' => function($query) {
                $query->withCount('students')
                      ->withCount('materials')
                      ->orderBy('created_at', 'desc');
            },
            'courses' => function($query) {
                $query->withCount('students')
                      ->withCount('materials')
                      ->orderBy('created_at', 'desc');
            }
        ]);

        // Add additional counts if needed
        $instructor->loadCount([
            'instructorCourses',
            'courses'
        ]);

        return view('instructors.show', compact('instructor'));
    }

    /**
     * DataTable endpoint for server-side processing
     */
    public function datatable(Request $request)
    {
        $query = User::role('instructor')
            ->with(['roles'])
            ->withCount([
                'instructorCourses as courses_count',
                'courses as total_courses_count'
            ]);

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchTerm = $request->search['value'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Ordering
        if ($request->has('order')) {
            $columns = ['id', 'name', 'email', 'created_at', 'courses_count'];
            $columnIndex = $request->order[0]['column'];
            $columnName = $columns[$columnIndex] ?? 'id';
            $direction = $request->order[0]['dir'] === 'asc' ? 'asc' : 'desc';
            
            $query->orderBy($columnName, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        $total = $query->count();
        $instructors = $query->skip($request->start)
                            ->take($request->length)
                            ->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $instructors->map(function($instructor) {
                return [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    'email' => $instructor->email,
                    'courses_count' => $instructor->courses_count ?? 0,
                    'total_courses_count' => $instructor->total_courses_count ?? 0,
                    'created_at' => $instructor->created_at->format('Y-m-d'),
                    'actions' => view('instructors.partials.actions', compact('instructor'))->render()
                ];
            })
        ]);
    }
}

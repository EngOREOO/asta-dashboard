<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\CareerLevel;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index(Request $request)
    {
        $query = Degree::query()->withCount('courses');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($s) use ($q) {
                $s->where('name', 'like', "%$q%");
            });
        }

        if ($request->filled('level')) {
            // Accept string levels like in the UI, map to integer for storage
            $level = $request->input('level');
            $map = [
                'certificate' => 1,
                'diploma' => 4,
                'bachelor' => 5,
                'master' => 6,
                'doctorate' => 7,
            ];
            if (isset($map[$level])) {
                $query->where('level', $map[$level]);
            }
        }

        if ($request->filled('duration_min')) {
            $query->where('duration_months', '>=', (int) $request->input('duration_min'));
        }
        if ($request->filled('duration_max')) {
            $query->where('duration_months', '<=', (int) $request->input('duration_max'));
        }

        if ($request->filled('credit_min')) {
            $query->where('credit_hours', '>=', (int) $request->input('credit_min'));
        }
        if ($request->filled('credit_max')) {
            $query->where('credit_hours', '<=', (int) $request->input('credit_max'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($request->filled('courses_order') && in_array($request->input('courses_order'), ['asc','desc'], true)) {
            $query->orderBy('courses_count', $request->input('courses_order'));
        } else {
            $query->latest();
        }

        $degrees = $query->paginate(20)->withQueryString();

        return view('degrees.index', [
            'degrees' => $degrees,
            'filters' => $request->only(['q','level','duration_min','duration_max','credit_min','credit_max','status','courses_order'])
        ]);
    }

    public function create()
    {
        $careerLevels = CareerLevel::orderBy('name')->get(['id','name']);
        return view('degrees.create', compact('careerLevels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:degrees',
            'code' => 'nullable|string|max:50|unique:degrees,code',
            'description' => 'nullable|string',
            'level' => 'required',
            'duration_months' => 'nullable|integer|min:1',
            'credit_hours' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['level'] = is_numeric($data['level'])
            ? (int) $data['level']
            : $this->mapLevelToInteger($data['level']);
        if (empty($data['name_ar'])) {
            $data['name_ar'] = $data['name'];
        }

        Degree::create($data);

        return redirect()->route('degrees.index')
            ->with('success', 'Degree created successfully.');
    }

    public function show(Degree $degree)
    {
        $degree->load(['courses.instructor']);

        return view('degrees.show', compact('degree'));
    }

    public function edit(Degree $degree)
    {
        $currentLevelString = $this->mapIntegerToLevel($degree->level);

        return view('degrees.edit', compact('degree', 'currentLevelString'));
    }

    public function update(Request $request, Degree $degree)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:degrees,name,'.$degree->id,
            'code' => 'nullable|string|max:50|unique:degrees,code,'.$degree->id,
            'description' => 'nullable|string',
            'level' => 'required',
            'duration_months' => 'nullable|integer|min:1',
            'credit_hours' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['level'] = is_numeric($data['level'])
            ? (int) $data['level']
            : $this->mapLevelToInteger($data['level']);
        if (empty($data['name_ar'])) {
            $data['name_ar'] = $data['name'];
        }

        $degree->update($data);

        return redirect()->route('degrees.index')
            ->with('success', 'Degree updated successfully.');
    }

    public function destroy(Degree $degree)
    {
        if ($degree->courses()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete degree with associated courses.']);
        }

        $degree->delete();

        return redirect()->route('degrees.index')
            ->with('success', 'Degree deleted successfully.');
    }

    /**
     * Map level string to integer value
     */
    private function mapLevelToInteger(string $level): int
    {
        return match ($level) {
            'certificate' => 1,
            'diploma' => 4,
            'bachelor' => 5,
            'master' => 6,
            'doctorate' => 7,
            default => 1
        };
    }

    /**
     * Map level integer to string value
     */
    private function mapIntegerToLevel(int $level): string
    {
        return match ($level) {
            1 => 'certificate',
            2 => 'intermediate',
            3 => 'secondary',
            4 => 'diploma',
            5 => 'bachelor',
            6 => 'master',
            7 => 'doctorate',
            default => 'certificate'
        };
    }
}

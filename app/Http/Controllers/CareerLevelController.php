<?php

namespace App\Http\Controllers;

use App\Models\CareerLevel;
use Illuminate\Http\Request;

class CareerLevelController extends Controller
{
    public function index(Request $request)
    {
        $query = CareerLevel::query();
        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->input('q').'%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }
        $levels = $query->latest()->paginate(20)->withQueryString();
        return view('career-levels.index', ['levels' => $levels, 'filters' => $request->only(['q','status'])]);
    }

    public function create()
    {
        return view('career-levels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        $data['is_active'] = true;
        CareerLevel::create($data);
        return redirect()->route('career-levels.index')->with('success', 'تم إنشاء المستوى بنجاح.');
    }

    public function edit(CareerLevel $career_level)
    {
        return view('career-levels.edit', ['level' => $career_level]);
    }

    public function update(Request $request, CareerLevel $career_level)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'is_active' => ['nullable','boolean'],
        ]);
        $career_level->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);
        return redirect()->route('career-levels.index')->with('success', 'تم تحديث المستوى.');
    }

    public function destroy(CareerLevel $career_level)
    {
        $career_level->delete();
        return redirect()->route('career-levels.index')->with('success', 'تم حذف المستوى.');
    }
}



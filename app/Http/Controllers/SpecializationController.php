<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;
use Illuminate\Support\Str;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::orderBy('sort_order')->orderBy('name')->get();
        return view('specializations.index', compact('specializations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:specializations,name'],
        ]);

        Specialization::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) ?: Str::random(6),
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return redirect()->route('specializations.index')->with('success', 'تمت إضافة التخصص');
    }

    public function toggleActive(Specialization $specialization)
    {
        $specialization->is_active = ! (bool) $specialization->is_active;
        $specialization->save();
        return redirect()->back()->with('success', 'تم تحديث حالة التخصص');
    }

    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return redirect()->back()->with('success', 'تم حذف التخصص');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CourseNote;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = CourseNote::with(['user', 'course'])
            ->latest()
            ->paginate(20);
            
        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
        ]);

        CourseNote::create($validated);

        return redirect()->route('notes.index')
            ->with('success', 'تم إضافة الملاحظة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseNote $note)
    {
        $note->load(['user', 'course']);
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseNote $note)
    {
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseNote $note)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'title' => 'required|string|max:255',
        ]);

        $note->update($validated);

        return redirect()->route('notes.index')
            ->with('success', 'تم تحديث الملاحظة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseNote $note)
    {
        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', 'تم حذف الملاحظة بنجاح');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CourseComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = CourseComment::with(['user', 'course'])
            ->latest()
            ->paginate(20);

        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comments.create');
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
        ]);

        CourseComment::create([
            'course_id' => $validated['course_id'],
            'user_id' => $validated['user_id'],
            'text' => $validated['content'],
            'is_approved' => true, // Auto-approve all comments
        ]);

        return redirect()->route('comments.index')
            ->with('success', 'تم إضافة التعليق بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseComment $comment)
    {
        $comment->load(['user', 'course']);

        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseComment $comment)
    {
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseComment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return redirect()->route('comments.index')
            ->with('success', 'تم تحديث التعليق بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseComment $comment)
    {
        $comment->delete();

        return redirect()->route('comments.index')
            ->with('success', 'تم حذف التعليق بنجاح');
    }
}

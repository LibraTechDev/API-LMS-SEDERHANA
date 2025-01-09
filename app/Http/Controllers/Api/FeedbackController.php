<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Student;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Endpoint untuk menambahkan feedback
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $feedback = Feedback::create([
            'course_id' => $courseId,
            'student_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'Feedback added successfully',
            'data' => $feedback,
        ]);
    }


    // Endpoint untuk menampilkan semua feedback pada course tertentu
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $feedbacks = $course->feedbacks()->with('student')->get();

        return response()->json([
            'code' => 200,
            'message' => 'Feedbacks retrieved successfully',
            'data' => $feedbacks,
        ]);
    }

    // Endpoint untuk mengedit feedback
    public function update(Request $request, $feedbackId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $feedback = Feedback::findOrFail($feedbackId);

        // Pastikan feedback ini milik siswa yang sedang login
        if ($feedback->student_id != Auth::id()) {
            return response()->json([
                'code' => 403,
                'message' => 'Unauthorized',
            ], 403);
        }

        $feedback->update(['content' => $request->content]);

        return response()->json([
            'code' => 200,
            'message' => 'Feedback updated successfully',
            'data' => $feedback,
        ]);
    }


    // Endpoint untuk menghapus feedback
    public function destroy($feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);

        // Pastikan feedback ini milik siswa yang sedang login
        if ($feedback->student_id != Auth::id()) {
            return response()->json([
                'code' => 403,
                'message' => 'Unauthorized',
            ], 403);
        }

        $feedback->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Feedback deleted successfully',
        ]);
    }

}
<?php
namespace App\Http\Controllers;

use App\Models\UserEnrollment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class EnrollmentController extends Controller
{
    // Show enrollment form
    public function showForm()
    {
        return view('Enroll');
    }

    // Store new enrollment (AJAX/JSON)
    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'username'         => 'required|string|max:255',
                'full_name'        => 'required|string|max:255',
                'phone'            => 'required|string|max:20',
                'email'            => 'required|email|max:255',
                'address'          => 'required|string|max:500',
                'additional_info'  => 'nullable|string|max:1000',
            ]);

            $enrollment = UserEnrollment::create($validated);

            return response()->json([
                'status' => 200,
                'message' => 'Enrollment submitted successfully!',
                'data' => $enrollment
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'errors' => $e->validator->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    // List all enrollments (AJAX/JSON)
    public function listEnrollments()
    {
        try {
            $enrollments = UserEnrollment::all();
            return response()->json([
                'status' => 200,
                'body' => $enrollments
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to load enrollments.'
            ], 500);
        }
    }

    // Show edit form (not AJAX)
    public function editEnrollment($id)
    {
        try {
            $enrollment = UserEnrollment::findOrFail($id);
            return view('edit_enrollment', compact('enrollment'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('enrollment.list')->with('error', 'Enrollment not found.');
        } catch (Exception $e) {
            return redirect()->route('enrollment.list')->with('error', 'An unexpected error occurred.');
        }
    }

    // Update enrollment (AJAX/JSON)
    public function updateEnrollment(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'username'         => 'required|string|max:255',
                'full_name'        => 'required|string|max:255',
                'phone'            => 'required|string|max:20',
                'email'            => 'required|email|max:255',
                'address'          => 'required|string|max:500',
                'additional_info'  => 'nullable|string|max:1000',
            ]);

            $enrollment = UserEnrollment::findOrFail($id);
            $enrollment->update($validated);

            return response()->json([
                'status' => 200,
                'message' => 'Enrollment updated successfully!',
                'data' => $enrollment
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'errors' => $e->validator->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Enrollment not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    // Delete enrollment (AJAX/JSON)
    public function deleteEnrollment($id)
    {
        try {
            $enrollment = UserEnrollment::findOrFail($id);
            $enrollment->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Enrollment deleted successfully!'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Enrollment not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    // Show single enrollment (optional, not AJAX)
    public function showEnrollment($id)
    {
        try {
            $enrollment = UserEnrollment::findOrFail($id);
            return view('show_enrollment', compact('enrollment'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('enrollment.list')->with('error', 'Enrollment not found.');
        } catch (Exception $e) {
            return redirect()->route('enrollment.list')->with('error', 'An unexpected error occurred.');
        }
    }
}
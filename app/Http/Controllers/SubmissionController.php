<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        // Validasi incoming request
        $request->validate([
            'name' => 'required|string',
            'submission_id' => 'required|string|unique:submissions',
            'payloads' => 'required|array',
        ]);

        // Simpan submission tanpa encode payloads
        $submission = Submission::create([
            'name' => $request->name,
            'submission_id' => $request->submission_id,
            'payloads' => $request->payloads // Sudah dalam bentuk array
        ]);
        
        return response()->json($submission, 201);
    }

    public function index()
    {
        return Submission::all();
    }
}

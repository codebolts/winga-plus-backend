<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\LegalDocument;
use Illuminate\Http\Request;

class LegalDocumentController extends Controller
{
    // Get all legal documents (admin only)
    public function index()
    {
        $documents = LegalDocument::latest()->get();
        return ApiResponse::success('Legal documents retrieved successfully', $documents);
    }

    // Get active legal documents (public)
    public function active()
    {
        $documents = LegalDocument::active()->get();
        return ApiResponse::success('Active legal documents retrieved successfully', $documents);
    }

    // Get specific document by type (public)
    public function show($type)
    {
        $document = LegalDocument::active()->ofType($type)->first();

        if (!$document) {
            return ApiResponse::error('Document not found', null, 404);
        }

        return ApiResponse::success('Document retrieved successfully', $document);
    }

    // Create new legal document (admin only)
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:terms_and_conditions,privacy_policy|unique:legal_documents,type',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'version' => 'string|max:10',
            'is_active' => 'boolean',
            'effective_date' => 'nullable|date'
        ]);

        $document = LegalDocument::create([
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->input('content'),
            'version' => $request->version ?? '1.0',
            'is_active' => $request->is_active ?? true,
            'effective_date' => $request->effective_date
        ]);

        return ApiResponse::success('Legal document created successfully', $document, 201);
    }

    // Update legal document (admin only)
    public function update(Request $request, $id)
    {
        $document = LegalDocument::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'version' => 'string|max:10',
            'is_active' => 'boolean',
            'effective_date' => 'nullable|date'
        ]);

        $document->update([
            'title' => $request->title,
            'content' => $request->input('content'),
            'version' => $request->version ?? $document->version,
            'is_active' => $request->is_active ?? $document->is_active,
            'effective_date' => $request->effective_date
        ]);

        return ApiResponse::success('Legal document updated successfully', $document);
    }

    // Delete legal document (admin only)
    public function destroy($id)
    {
        $document = LegalDocument::findOrFail($id);
        $document->delete();

        return ApiResponse::success('Legal document deleted successfully');
    }

    // Get terms and conditions (public)
    public function termsAndConditions()
    {
        return $this->show('terms_and_conditions');
    }

    // Get privacy policy (public)
    public function privacyPolicy()
    {
        return $this->show('privacy_policy');
    }
}

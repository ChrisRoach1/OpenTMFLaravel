<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentMetadata\CreateMetadataRequest;
use App\Models\DocumentMetadata;
use App\Models\MetadataType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FileUploadController extends Controller
{
    public function index(Request $request): Response
    {
        $documentMetadata = DocumentMetadata::query()->with('metadataType')->get();

        return Inertia::render('File-Upload',
            [
                'documentMetadata' => $documentMetadata,
            ]);
    }

    public function create(CreateMetadataRequest $request)
    {
        return redirect()->route('file-upload.index');
    }
}

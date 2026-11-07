<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentMetadata\CreateMetadataRequest;
use App\Models\DocumentMetadata;
use App\Models\MetadataType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DocumentMetadataController extends Controller
{
    public function index(Request $request): Response
    {
        $metadataTypes = MetadataType::query()->get();
        $documentMetadata = DocumentMetadata::query()->with('metadataType')->get();

        return Inertia::render('Metadata-Configuration',
            [
                'metadataTypes' => $metadataTypes,
                'documentMetadata' => $documentMetadata,
            ]);
    }

    public function create(CreateMetadataRequest $request)
    {
        DocumentMetadata::create($request->validated());

        return redirect()->route('metadata-configuration.index');
    }
}

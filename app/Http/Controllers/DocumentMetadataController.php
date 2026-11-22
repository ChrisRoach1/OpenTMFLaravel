<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentMetadata\CreateMetadataRequest;
use App\Http\Requests\DocumentMetadata\UpdateMetadataRequest;
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
                'documentsAdded' => false, // TODO: replace with actual logic
            ]);
    }

    public function create(CreateMetadataRequest $request)
    {
        DocumentMetadata::create($request->validated());

        return redirect()->route('metadata-configuration.index');
    }

    public function update(UpdateMetadataRequest $request, DocumentMetadata $documentMetadata)
    {
        $documentMetadata->update($request->validated());
        $documentMetadata->save();

        return redirect()->route('metadata-configuration.index');
    }

    public function delete(DocumentMetadata $documentMetadata)
    {
        $documentMetadata->delete();

        return redirect()->route('metadata-configuration.index');
    }
}

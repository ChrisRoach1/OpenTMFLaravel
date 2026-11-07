<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentMetadata\CreateMetadataRequest;
use App\Models\MetadataType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DocumentMetadataController extends Controller
{
    public function index(Request $request): Response
    {
        $metadataTypes = MetadataType::query()->get();

        return Inertia::render('Metdata-Configuration',
            [
                'metadataTypes' => $metadataTypes,
            ]);
    }

    public function create(CreateMetadataRequest $request)
    {
        dd($request);

        return \response('test');
    }
}

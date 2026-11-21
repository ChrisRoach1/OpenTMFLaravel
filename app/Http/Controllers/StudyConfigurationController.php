<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudyConfiguration\UpdateStudyConfigurationRequest;
use App\Models\Study;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudyConfigurationController extends Controller
{
    public function index(Request $request): Response
    {
        $study = Study::first();
        return Inertia::render('Study-Configuration', [
            'study' => $study,
        ]);
    }

    public function update(UpdateStudyConfigurationRequest $request, Study $study): \Illuminate\Http\RedirectResponse
    {
        $study->update($request->validated());
        $study->save();

        return redirect()->route('study-configuration.index');
    }
}

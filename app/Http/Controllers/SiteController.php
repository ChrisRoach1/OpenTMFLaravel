<?php

namespace App\Http\Controllers;

use App\Http\Requests\Site\CreateSiteRequest;
use App\Http\Requests\Site\UpdateSiteRequest;
use App\Models\Country;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('site-configuration', [
            'sites' => Site::with('country')->get()->all(),
            'countries' => Country::all(),
        ]);
    }

    public function store(CreateSiteRequest $request): RedirectResponse
    {
        Site::create($request->validated());

        return redirect()->route('site-configuration.index');
    }

    public function update(UpdateSiteRequest $request, Site $site): RedirectResponse
    {
        $site->update($request->validated());
        $site->save();

        return redirect()->route('site-configuration.index');
    }

    public function delete(Site $site): RedirectResponse
    {
        $site->delete();

        return redirect()->route('site-configuration.index');
    }
}

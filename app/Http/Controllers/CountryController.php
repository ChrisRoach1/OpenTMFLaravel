<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\CreateCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('country-configuration', [
            'countries' => Country::all(),
        ]);
    }

    public function store(CreateCountryRequest $request): RedirectResponse
    {
        Country::create($request->validated());

        return redirect()->route('country-configuration.index');
    }

    public function update(UpdateCountryRequest $request, Country $country): RedirectResponse
    {
        $country->update($request->validated());
        $country->save();

        return redirect()->route('country-configuration.index');
    }

    public function delete(Country $country): RedirectResponse
    {
        $country->delete();

        return redirect()->route('country-configuration.index');
    }
}

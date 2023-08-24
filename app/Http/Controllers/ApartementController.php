<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Apartement;
use App\Models\Residence;

class ApartementController extends Controller
{
    public function index(Request $request)
    {
        $residenceID = $request->input('residence_id');
        $search = $request->input('search');
        $status = $request->input('status');
    
        $query = Apartement::query();
    
        if ($residenceID) {
            $query->where('ResidenceID', $residenceID);
        }
    
        if ($search) {
            $query->where('ApartmentsNumber', 'LIKE', '%' . $search . '%');
        }
    
        if ($status) {
            $query->where('Status', $status);
        }
    
        $apartments = $query->paginate(10);
    
        return view('apartments.index', compact('apartments', 'residenceID'));
    }
    

    
    
    public function show(Apartement $apartment)
    {
        return view('apartments.show', compact('apartment'));
    }

    public function create()
    {
        if (Gate::denies('is-admin')) {
            abort(403, 'Unauthorized');
        }
        $residences = Residence::all();
        return view('apartments.create', compact('residences'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('is-admin')) {
            abort(403, 'Unauthorized');
        }
        Apartement::create($request->all());
        return redirect()->route('apartments.index')->with('success', 'Apartment created successfully.');
    }

    public function edit(Apartement $apartment)
    {
        if (Gate::denies('is-admin')) {
            abort(403, 'Unauthorized');
        }
        $residences = Residence::all();
        return view('apartments.edit', compact('apartment', 'residences'));
    }

    public function update(Request $request, Apartement $apartment)
    {
        if (Gate::denies('is-admin')) {
            abort(403, 'Unauthorized');
        }
        $apartment->update($request->all());
        return redirect()->route('apartments.index')->with('success', 'Apartment updated successfully.');
    }

    public function destroy(Apartement $apartment)
    {
        if (Gate::denies('is-admin')) {
            abort(403, 'Unauthorized');
        }
        $apartment->delete();
        return redirect()->route('apartments.index')->with('success', 'Apartment deleted successfully.');
    }
}

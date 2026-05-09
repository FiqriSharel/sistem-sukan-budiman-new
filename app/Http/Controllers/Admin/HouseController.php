<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HouseRequest;
use App\Models\AuditLog;
use App\Models\House;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.houses.index', [
            'houses' => House::withCount('participants')->orderBy('name')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.houses.create', ['house' => new House]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HouseRequest $request)
    {
        $house = House::create($request->validated());
        AuditLog::record('create', $house, null, $house->toArray());

        return redirect()->route('admin.houses.index')->with('success', 'Rumah sukan berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        return redirect()->route('admin.houses.edit', $house);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        return view('admin.houses.edit', compact('house'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HouseRequest $request, House $house)
    {
        $oldValues = $house->toArray();
        $house->update($request->validated());
        AuditLog::record('update', $house, $oldValues, $house->toArray());

        return redirect()->route('admin.houses.index')->with('success', 'Rumah sukan berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        if ($house->participants()->exists()) {
            return back()->with('error', 'Rumah sukan ini masih mempunyai peserta.');
        }

        $oldValues = $house->toArray();
        $house->delete();
        AuditLog::record('delete', $house, $oldValues, null);

        return redirect()->route('admin.houses.index')->with('success', 'Rumah sukan berjaya dipadam.');
    }
}

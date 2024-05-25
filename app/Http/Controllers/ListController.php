<?php

namespace App\Http\Controllers;

use App\Models\BuyList;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $BuyLists = BuyList::all();
        // dd($BuyLists);
        return view('index', compact('BuyLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        BuyList::create($request->validate([
            'item' => 'required',
            'quantity' => 'required|numeric'
        ]));
        return back()->with('success-store', 'Data Stored Succed!!');
        
        // Simpan data ke dalam tabel buylists
        

        // return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        BuyList::find($id)->update($request->validate([
            'item' => 'required',
            'quantity' => 'required|numeric'
        ]));

        return redirect()->back()->with('success', 'Success Edit Item!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BuyList::find($id)->delete();

        return redirect()->back()->with('success', 'Berhasil Menghapus Item!');
    }

    public function updateStatus(Request $request, $id)
    {
        // dd('berhasil');
        $task = BuyList::findOrFail($id);
        $task->status = $request->input('status');
        $task->save();

        return response()->json(['status' => $task->status]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();

        return view('sales.input', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_transaksi' => 'required|date',
            'kategori_id' => 'required|exists:kategoris,id',
            'nominal' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sales = Sales::create([
            'user_id' => auth()->id(),
            'tanggal_transaksi' => $request->input('tanggal_transaksi'),
            'kategori_id' => $request->input('kategori_id'),
            'nominal' => $request->input('nominal'),
        ]);

        return redirect()->route('dashboard')->with('success', 'data sales berhasil di simpan!.');
    }

    public function edit($id)
    {
        $kategoris = Kategori::all();
        $sales = Sales::findOrFail($id);

        return view('sales.edit', compact('id', 'kategoris', 'sales'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_transaksi' => 'required|date',
            'kategori_id' => 'required|exists:kategoris,id',
            'nominal' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sales = Sales::find($id);

        if (!$sales) {
            return redirect()->route('dashboard')->with('error', 'Data tidak ditemukan.');
        }

        $sales->update([
            'tanggal_transaksi' => $request->input('tanggal_transaksi'),
            'kategori_id' => $request->input('kategori_id'),
            'nominal' => $request->input('nominal')
        ]);

        return redirect()->route('dashboard')->with('success', 'Data sales berhasil diperbarui.');
    }


    public function delete($id)
    {
        $sales = Sales::findOrFail($id)->delete();

        return redirect()->back();
    }
}

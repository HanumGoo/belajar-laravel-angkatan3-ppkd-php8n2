<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;

class PesertaController extends Controller
{
    public function index()
    {
        // GET
        $pesertas = Peserta::get('*');
        $title = "Data Peserta";
        return view('peserta.index', compact('pesertas', 'title'));
    }

    // peserta/create

    public function create()
    {
        // GET
        $title = "Tambah Peserta";
        return view('peserta.create', compact('title'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'email' => 'required|email|unique:pesertas,email',
            'umur' => 'required',
            'address' => 'nullable',
        ]);

        //INSERT INTO pesertas () values ()
        Peserta::create([
            'name' => $request->nama,
            'email' => $request->email,
            'age' => $request->umur,
            'address' => $request->address,
        ]);

        return redirect()->to('peserta');
    }

    // peserta/edit

    public function edit(int $id)
    {
        $title = "Edit";
        $peserta = Peserta::find($id);
        return view('peserta.edit', compact('peserta', 'title'));
    }
    public function update(int $id, Request $request)
    {
        $peserta = Peserta::find($id);
        $peserta->update([
            'name' => $request->nama,
            'email' => $request->email,
            'age' => $request->umur,
            'address' => $request->address,

        ]);
        return redirect()->to('peserta');
    }
    public function delete(int $id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->delete();
        return redirect()->to('peserta');
    }


}

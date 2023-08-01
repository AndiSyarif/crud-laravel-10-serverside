<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::orderBy('name', 'asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('price', function ($data) {
                    return "Rp. " . number_format($data->price, 0);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $editButton = '<form class="d-inline" action="/barang/' . $row->id_barang . '/edit" method="GET">
                       <button type="submit" class="btn btn-success btn-sm mr-1"><i class="fa-solid fa-pen"></i> Edit</button> </form>';
                    $btn .= $editButton;
                    $deleteButton = '<form class="d-inline" action=/barang/' . $row->id_barang . '
                       method="POST">
                       <input type="hidden" name="_token" value=' . csrf_token() . '>
                       <input type="hidden" name="_method" value="delete"> 
                       <button type="submit" id="btn-delete" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i> Delete</button>';
                    $btn .= $deleteButton;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('barang.barang', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.barang-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100|unique:barangs',
            'category' => 'required',
            'supplier' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'note' => 'max:1000',
        ]);

        $barang = Barang::create($request->all());

        Alert::success('Success', 'Barang has been saved !');
        return redirect('/barang');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_barang)
    {
        $barang = barang::findOrFail($id_barang);

        return view('barang.barang-edit', [
            'barang' => $barang,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_barang)
    {
        $validated = $request->validate([
            'name' => 'required|max:100|unique:barangs,name,' . $id_barang . ',id_barang',
            'category' => 'required',
            'supplier' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'note' => 'max:1000',
        ]);

        $barang = Barang::findOrFail($id_barang);
        $barang->update($validated);

        Alert::info('Success', 'Barang has been updated !');
        return redirect('/barang');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_barang)
    {
        try {
            $deletedbarang = Barang::findOrFail($id_barang);

            $deletedbarang->delete();

            Alert::error('Success', 'Barang has been deleted !');
            return redirect('/barang');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Barang already used !');
            return redirect('/barang');
        }
    }
}

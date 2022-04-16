<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;

use App\Models\Cart;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\User;
use App\Models\Member;
use App\Models\Category;
use App\Models\Collection;

class LoanController extends Controller
{
    public function index()
    {
        $data['title']  =   'Transaksi Peminjaman';

        if(request()->ajax()) {
            return datatables()->of(Loan::with(['officer', 'member'])->orderBy('created_at', 'desc')->get())
                ->editColumn('status', function ($data) {
                    if($data->return_date < \Carbon\Carbon::now()->toDateString()) {
                        $stt    =   '<h5><span class="badge badge-danger">Terlambat</span></h5>';
                    } else {
                        if($data->status == 'Dipinjam') {
                            $stt    =   '<h5><span class="badge badge-info">' . $data->status . '</span></h5>';
                        } elseif($data->status == 'Dikembalikan') {
                            $stt    =   '<h5><span class="badge badge-success">' . $data->status . '</span></h5>';
                        }
                    }

                    return $stt;
                })
                ->addColumn('action', function ($data) {
                    $button =   '<a href="' . route('loan.show', $data->id) . '" class="btn btn-info ml-2" title="Detail"><i class="fas fa-eye"></i></a>';

                    return $button;
                })->rawColumns(['status', 'action'])->addIndexColumn()->make(true);
        }

        return view('transaction.loan.index', $data);
    }

    public function create()
    {
        $data['title']      =   'Form Peminjaman';
        $data['button']     =   'Simpan';
        $data['cart']       =   Cart::with(['collection'])->where('officer_id', Auth::user()->id)->get();
        $data['member']     =   Member::get();
        $data['category']   =   Category::get();

        return view('transaction.loan.form', $data);
    }

    public function store(Request $request)
    {
        $val    =   Validator::make($request->all(), [
                        'member_id'         =>  'required|exists:members,id',
                        'return_date'       =>  'required|date|after:today',
                    ],
                    [
                        'member_id.required'        =>  'Member wajib dipilih',
                        'member_id.exists'          =>  'Member tidak ditemukan',
                        'return_date.required'      =>  'Tanggal Pengembalian wajib diisi',
                        'return_date.date'          =>  'Tanggal Pengembalian tidak valid',
                        'return_date.after'         =>  'Tanggal Pengembalian wajib setelah tanggal sekarang',
                    ]);

        if($val->fails()) {
            return response()->json(['errors' => $val->errors()]);
        }

        $cart       =   Cart::where('officer_id', Auth::user()->id)->get();

        if($cart->isEmpty()) {
            return response()->json(['err' => 'Keranjang wajib diisi']);
        } else {
            $loan               =   new Loan;
            $loan->code         =   uniqid(15);
            $loan->officer_id   =   Auth::user()->id;
            $loan->member_id    =   $request->member_id;
            $loan->loan_date    =   \Carbon\Carbon::now()->toDateString();
            $loan->return_date  =   $request->return_date;
            $loan->status       =   'Dipinjam';
            $loan->save();

            foreach($cart as $row) {
                $loan_det                   =   new LoanDetail;
                $loan_det->loan_id          =   $loan->id;
                $loan_det->collection_id    =   $row->collection_id;
                $loan_det->quantity         =   '1';
                $loan_det->save();
            }

            foreach($cart as $row) {
                $row->delete();
            }

            return response()->json(['success' => 'Peminjaman Berhasil Disimpan']);
        }
    }

    public function show($id)
    {
        $data['title']  =   'Detail Transaksi Peminjaman';
        $data['loan']   =   Loan::with(['officer', 'member', 'loanDetail'])->findOrFail($id);

        return view('transaction.loan.detail', $data);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getCollection(Request $request)
    {
        $collection =   Collection::where('category_id', $request->category_id)
                                    ->where('stock', '!=', '0')
                                    ->get();

        return response()->json($collection);
    }

    public function addCart(Request $request)
    {
        $val    =   Validator::make($request->all(), [
                        'category_id'   =>  'required|exists:categories,id',
                        'collection_id' =>  'required|exists:collections,id',
                    ],
                    [
                        'category_id.required'      =>  'Kategori wajib dipilih',
                        'category_id.exists'        =>  'Kategori tidak ditemukan',
                        'collection_id.required'    =>  'Koleksi wajib dipilih',
                        'collection_id.exists'      =>  'Koleksi tidak ditemukan',
                    ]);

        if($val->fails()) {
            return response()->json(['errors' => $val->errors()]);
        }

        $data   =   Cart::where('officer_id', Auth::user()->id)->where('collection_id', $request->collection_id)->first();

        if(empty($data)) {
            $cart                   =   new Cart;
            $cart->officer_id       =   Auth::user()->id;
            $cart->collection_id    =   $request->collection_id;
            $cart->save();
        }

        return response()->json(['success' => 'Koleksi Berhasil Ditambah']);
    }

    public function deleteCart($id)
    {
        $cart   =   Cart::findOrFail($id);
        $cart->delete();

        return response()->json(['success' => 'Koleksi Berhasil Dihapus']);
    }
}

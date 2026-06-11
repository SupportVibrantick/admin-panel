<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\AdminBankDetail;
use Illuminate\Http\Request;

class UserBankDetailController extends Controller
{
    public function index()
    {
        $bankDetails = AdminBankDetail::where('is_active', true)
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.user.admin-bank-detail', compact('bankDetails'));
    }

    public function viewDetail($id)
    {
        $bankDetail = AdminBankDetail::findOrFail($id);
        return response()->json($bankDetail);
    }
}
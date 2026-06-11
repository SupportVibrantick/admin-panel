<?php

namespace App\Http\Controllers;

use App\Models\AdminBankDetail;
use Illuminate\Http\Request;

class BankAccountSettingController extends Controller
{
    public function index()
    {       
        $bankDetails = AdminBankDetail::first(); // Assuming you have only one set of bank details
        return view('admin.pages.MLM.bank-account-settings', compact('bankDetails'));
    }

    public function update(Request $request, $bankDetailId)
    {
        $bankDetail = AdminBankDetail::findOrFail($bankDetailId);   
        $validated = $request->validate([
            'mode_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

            $bankDetail = $bankDetail->update([
                    'mode_name' => $validated['mode_name'],
                    'address' => $validated['address'],
                    'account_no' => $validated['account_no'],
                    'bank_name' => $validated['bank_name'],
                    'ifsc_code' => $validated['ifsc_code'],
                    'is_active' => $validated['is_active'],
                    //  'image' => $validated['image'] ?? $bankDetail->image, // Keep
                ]);

        return back()->with('success', 'Bank account updated successfully.');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function index(Request $request)
    {
        
        return response()->json(['success' => true, 'message' => 'KYC details retrieved successfully', ]);
    }

    // public function submit(Request $request, )
    // {

    //     // return response()->json($request->all());
    //     $request->validate([
    //         'pan_number' => 'required|string|max:10',
    //         'aadhaar_number' => 'required|string|max:12',
    //         'pan_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //         'aadhaar_front_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //         'aadhaar_back_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //         'bank_document_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $kyc = Kyc::updateOrCreate(
    //         [
    //             'user_id' => $request->user_id,
    //         ],
    //         [
    //             'pan_number' => $request->pan_number,
    //             'aadhaar_number' => $request->aadhaar_number,
    //             'pan_image' => $request->file('pan_image')->store('kyc/pan', 'public'),

    //             'aadhaar_front_image' => $request->file('aadhaar_front_image')->store('kyc/aadhaar/front', 'public'),

    //             'aadhaar_back_image' => $request->file('aadhaar_back_image')->store('kyc/aadhaar/back', 'public'),

    //             'bank_document_image' => $request->file('bank_document_image')->store('kyc/bank', 'public'),

    //             'status' => 'pending',
    //             'reject_reason' => null,
    //         ]
    //     );

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'KYC submitted successfully',
    //         'data' => $kyc
    //     ]);
    // }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'pan_number' => 'required|string|max:10',
            'aadhaar_number' => 'required|string|max:12',
            'pan_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'aadhaar_front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'aadhaar_back_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bank_document_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $kyc = Kyc::firstOrNew([
            'user_id' => $request->user_id
        ]);

        $kyc->pan_number = $request->pan_number;
        $kyc->aadhaar_number = $request->aadhaar_number;
        $kyc->status = 'pending';
        $kyc->reject_reason = null;

        // PAN Image
        if ($request->hasFile('pan_image')) {
            $filename = time() . '_pan.' . $request->file('pan_image')->getClientOriginalExtension();
            $request->file('pan_image')->move(public_path('kyc/pan'), $filename);
            $kyc->pan_image = 'kyc/pan/' . $filename;
        }

        // Aadhaar Front
        if ($request->hasFile('aadhaar_front_image')) {
            $filename = time() . '_aadhaar_front.' . $request->file('aadhaar_front_image')->getClientOriginalExtension();
            $request->file('aadhaar_front_image')->move(public_path('kyc/aadhaar/front'), $filename);
            $kyc->aadhaar_front_image = 'kyc/aadhaar/front/' . $filename;
        }

        // Aadhaar Back
        if ($request->hasFile('aadhaar_back_image')) {
            $filename = time() . '_aadhaar_back.' . $request->file('aadhaar_back_image')->getClientOriginalExtension();
            $request->file('aadhaar_back_image')->move(public_path('kyc/aadhaar/back'), $filename);
            $kyc->aadhaar_back_image = 'kyc/aadhaar/back/' . $filename;
        }

        // Bank Document
        if ($request->hasFile('bank_document_image')) {
            $filename = time() . '_bank.' . $request->file('bank_document_image')->getClientOriginalExtension();
            $request->file('bank_document_image')->move(public_path('kyc/bank'), $filename);
            $kyc->bank_document_image = 'kyc/bank/' . $filename;
        }

        $kyc->save();

        return response()->json([
            'status' => true,
            'message' => 'KYC submitted successfully',
            'data' => $kyc
        ]);
    }
}

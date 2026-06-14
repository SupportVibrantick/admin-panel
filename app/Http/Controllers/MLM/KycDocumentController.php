<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KycDocumentController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $kyc = Kyc::select(
                'kycs.*',
                'mlm_users.first_name',
                'mlm_users.last_name',
                'mlm_users.user_name'
            )
            ->leftJoin('mlm_users', 'kycs.user_id', '=', 'mlm_users.id');

            return DataTables::of($kyc)
                ->addIndexColumn()

                ->addColumn('name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })

                ->addColumn('username', function ($row) {
                    return $row->user_name;
                })
                ->addColumn('pan_number', function ($row) {
                    return $row->pan_number;
                })
                ->addColumn('aadhaar_number', function ($row) {
                    return $row->aadhaar_number;
                })            
                ->addColumn('status', function ($row) {

                    if ($row->status == 'pending') {
                        return '<span class="badge bg-warning">Pending</span>';
                    }

                    if ($row->status == 'approved') {
                        return '<span class="badge bg-success">Approved</span>';
                    }

                    if ($row->status == 'rejected') {
                        return '<span class="badge bg-danger">Rejected</span>';
                    }

                    return '<span class="badge bg-secondary">'
                        . ucfirst($row->status) .
                        '</span>';
                })
                ->filterColumn('status', function($query, $keyword) {
                    $query->where('status', 'like', "%{$keyword}%");
                })  
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('actions', function ($row) {

                    return '
                        <button
                            class="btn btn-sm btn-primary view-kyc-btn"
                            data-id="'.$row->id.'">
                            <i class="fas fa-eye"></i> View Kyc
                        </button>
                    ';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
        
        return view('admin.pages.mlm.kyc-documents');
    }

    public function viewKyc($id)
    {
        $kyc = Kyc::with([
            'user' => function ($query) {
                $query->select('id', 'user_name', 'first_name', 'last_name');
            }
        ])->findOrFail($id);

        $imageFields = [
            'pan_image',
            'aadhaar_front_image',
            'aadhaar_back_image',
            'bank_document_image'
        ];

        foreach ($imageFields as $field) {
            if (!empty($kyc->$field)) {
                $kyc->$field = asset($kyc->$field);
            }
        }

        return response()->json($kyc);
    }


    public function updateKyc(Request $request, $id)
    {

        // dd($request->all(), $id);
        $v = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'reject_reason' => 'nullable|string',
        ]);

        $fundRequest = Kyc::findOrFail($id);
        $fundRequest->update([
            'status' => $v['status'], 
            'reject_reason' => $v['reject_reason']
            ]);

        // if ($v['status'] === 'approved') {
             
        // }

        return response()->json([
            'success' => true,
            'message' => 'KYC updated successfully'
            ]);
    }
}

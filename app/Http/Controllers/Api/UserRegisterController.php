<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MlmActivationMail;
use App\Mail\MlmUserWelcomeMail;
use App\Models\MLMTree;
use App\Models\MlmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Pest\Support\Str;

class UserRegisterController extends Controller
{


    public function register(Request $request)
    {
        // return response()->json($request->all());

       

        $validator = Validator::make($request->all(), [
            'user_name'         => 'required|string|max:50|unique:mlm_users,user_name',
            'sponsor'          => 'required|string|exists:mlm_users,user_name',
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:mlm_users,email',
            'phone'            => 'required|string|max:15|unique:mlm_users,phone',
            'pan_number' => [
                'required',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'unique:mlm_users_details,pan_number'
            ],
            'password'         => 'required|min:6|confirmed',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string',
            'district' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'pincode' => 'nullable|string',
        ]);

        

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sponsor = MlmUser::where('is_active', true)->where('is_deleted', false)->where('user_name', $request->sponsor)->first();
        // return response()->json($sponsor);

        if (!$sponsor) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid sponsor username.'
            ], 422);
        }

        $user = MlmUser::create([
            'user_name'     => $request->user_name,
            'track_id'      => 'TRK' . date('Y') . strtoupper(Str::random(6)) . time(),
            'sponsor_id'    => $sponsor->id,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'password'      => Hash::make($request->password),
            'position_in_sponsor_leg' => 'none',
            'membership_type' => 'CUSTOMER',
            'is_active' => false,
            'is_verified' => false,
            'is_deleted' => false,
            'verification_token' => Str::random(60),
            'verification_expires' => now()->addHours(24),
        ]);

        $user->detail()->create([
            'pan_number'      => strtoupper($request->pan_number),
            'address_line_1'  => $request->address_line_1,
            'address_line_2'  => $request->address_line_2,
            'city'            => $request->city,
            'district'        => $request->district,
            'state'           => $request->state,
            'country'         => $request->country ?? 'India',
            'pincode'         => $request->pincode,
        ]);


        MLMTree::create([   
            'mlm_user_id' => $user->id,
            'parent_id' => null,
            'position' => 'none',
            'level' => 0,
        ]);
        $activationUrl = route('mlm.activate', ['token' => $user->verification_token]);
        try {
            Mail::to($user->email)->send(new MlmActivationMail($user, $activationUrl));

            Mail::to($user->email)->send(new MlmUserWelcomeMail($user));

        } catch (\Exception $e) {
            Log::error('Failed to send MLM emails', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        } 

        return response()->json([
            'status' => true,
            'message' => 'Registration successful.',
            'data' => $user
        ], 201);
    }
}

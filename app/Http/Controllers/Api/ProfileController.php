<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MlmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = MlmUser::with([      
                'detail',
                'sponsor:id,user_name,first_name,last_name'
            ])->findOrFail($request->user_id);

            if ($user->detail && $user->detail->profile_image) {
                $user->detail->profile_image = asset('storage/' . $user->detail->profile_image);
            }else{
                 $user->detail->profile_image = 'http://127.0.0.1:8001/assets/images/logo.webp';
            }

        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully',
            'data' => $user,
        ]);
    }

     
    public function updateProfile(Request $request)
    {
        $user =  MlmUser::findOrFail($request->user_id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // 'email' => 'nullable|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:15',
            // 'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_relation' => 'nullable|string|max:100',
        ]);

        $userData = [
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $user->email,
            'mobile'     => $validated['mobile'] ?? null,
        ];

        $detailData = [
            'date_of_birth'    => $validated['date_of_birth'] ?? null,
            'gender'           => $validated['gender'] ?? null,
            'father_name'      => $validated['father_name'] ?? null,
            'mother_name'      => $validated['mother_name'] ?? null,
            'address_line_1'   => $validated['address_line_1'] ?? null,
            'address_line_2'   => $validated['address_line_2'] ?? null,
            'city'             => $validated['city'] ?? null,
            'district'         => $validated['district'] ?? null,
            'state'            => $validated['state'] ?? null,
            'country'          => $validated['country'],
            'pincode'          => $validated['pincode'] ?? null,
            'nominee_name'     => $validated['nominee_name'] ?? null,
            'nominee_relation' => $validated['nominee_relation'] ?? null,
        ];
         

        // if ($request->hasFile('profile_image')) {

        //     $file = $request->file('profile_image');

        //     $filename = time() . '.' . $file->getClientOriginalExtension();

        //     $path = $file->storeAs(
        //         'profile_images',
        //         $filename,
        //         'public'
        //     );

        //     $detailData['profile_image'] = $path;
        // }

        $user->update($userData);
        $user->detail()->updateOrCreate(
            ['user_id' => $user->id],
            $detailData
        );

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:mlm_users,id',
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = MlmUser::find($request->user_id);

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Old password is incorrect.'
            ], 400);
        }

        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'New password must be different from the current password.'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.'
        ]);
    }


    public function updateImage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = MlmUser::findOrFail($request->user_id);

        // Delete old image
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Upload new image
        $imagePath = $request->file('profile_image')
            ->store('profile-images', 'public');

        $user->detail()->updateOrCreate(
            ['user_id' => $user->id],
            ['profile_image' => $imagePath]
        );

        return response()->json([
            'status' => true,
            'message' => 'Profile image updated successfully.',
            'image_url' => asset('storage/' . $imagePath),
            'data' => $user->profile_image
        ]);
    }
}

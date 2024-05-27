<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $staff = auth('staff')->user();
        $modules = Module::with('permissions:id,module_id')->orderBy('name', 'asc')->get();

        return view('pages.profile.index', compact('staff', 'modules'));
    }

    /**
     * update
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone',
            'phone' => 'required_without:email|max:50',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,bmp,jpeg,webp|max:255',
        ]);

        $staff = Staff::find(auth('staff')->id());

        if (! empty($request->email)) {
            $email = Staff::where(function ($query) use ($request, $staff) {
                $query->where('email', $request->email)->where('email', '!=', $staff->email)->first();
            })
                ->first();
            if ($email) {
                return response()->json([
                    'errors' => ['email' => ['The email has already been taken.']],
                ], 422);
            }
        }
        if (! empty($request->phone)) {
            $phone = Staff::where(function ($query) use ($request, $staff) {
                $query->where('phone', $request->phone)->where('phone', '!=', $staff->phone)->first();
            })
                ->first();
            if ($phone) {
                return response()->json([
                    'errors' => ['phone' => ['The phone has already been taken.']],
                ], 422);
            }
        }

        $input = $request->all();
        if ($request->image) {
            $input['image'] = file_upload($request->image, 'staff', $staff->image);
        }
        $staff->update($input);

        return response()->json(['message' => 'Your profile updated successfully.']);
    }

    /**
     * show update password form
     */
    public function showUpdatePasswordForm()
    {
        return view('pages.profile.update-password');
    }

    /**
     * update password
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        $staff = Auth::guard('staff')->user();

        if (Hash::check($request->current_password, $staff->password)) {

            if (! Hash::check($request->password, $staff->password)) {

                $authUser = Staff::find($staff->id);
                $authUser->update([
                    'password' => Hash::make($request->password),
                ]);

                Auth::guard('staff')->logout();

                return response()->json(['message' => 'Password updated successfully']);

            } else {

                return response()->json(['message' => 'New password can not be same as current password'], 302);
            }

        } else {
            return response()->json(['message' => 'Password does not match'], 302);
        }
    }
}

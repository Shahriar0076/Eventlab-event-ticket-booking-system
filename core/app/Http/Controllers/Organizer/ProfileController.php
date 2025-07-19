<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use App\Rules\FileTypeValidate;

class ProfileController extends Controller
{
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $organizer = authOrganizer();
        return view('Template::organizer.profile_setting', compact('pageTitle', 'organizer'));
    }

    public function submitProfile(Request $request)
    {
        $organizer = authOrganizer();
        $request->validate([
            'organization_name' => [
                'required',
                'string',
                Rule::unique('organizers')->ignore($organizer->id),
            ],
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'title'             => 'nullable|string',
            'short_description' => 'nullable|string',
            'long_description'  => 'nullable|string',
            'profile_image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cover_image'       => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required' => 'The last name field is required'
        ]);

        $organizer->firstname = $request->firstname;
        $organizer->lastname = $request->lastname;

        $organizer->address = $request->address;
        $organizer->city = $request->city;
        $organizer->state = $request->state;
        $organizer->zip = $request->zip;

        if ($request->profile_image) {
            try {
                $organizer->profile_image = fileUploader($request->profile_image, getFilePath('organizerProfile'), getFileSize('organizerProfile'), $organizer->profile_image);
            } catch (\Exception $exp) {
                return returnBack('Couldn\'t upload profile image');
            }
        }
        if ($request->cover_image) {
            try {
                $organizer->cover_image = fileUploader($request->cover_image, getFilePath('organizerCover'), getFileSize('organizerCover'), $organizer->cover_image);
            } catch (\Exception $exp) {
                return returnBack('Couldn\'t upload cover image');
            }
        }

        $organizer->organization_name = $request->organization_name;
        $organizer->slug              = slug($request->organization_name);
        $organizer->title             = $request->title;
        $organizer->short_description = $request->short_description;
        $organizer->long_description  = $request->long_description;
        $organizer->save();

        $organizer->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view('Template::organizer.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);

        $organizer = authOrganizer();
        if (Hash::check($request->current_password, $organizer->password)) {
            $password = Hash::make($request->password);
            $organizer->password = $password;
            $organizer->save();
            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }
}

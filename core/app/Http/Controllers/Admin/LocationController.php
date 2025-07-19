<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class LocationController extends Controller {
    public function index() {
        $pageTitle = "Manage Location";
        $locations = Location::orderBy('sort_order')->withCount('events')->paginate(getPaginate());
        return view('admin.location.index', compact('pageTitle', 'locations'));
    }

    public function store(Request $request, $id = 0) {
        $imgRequired = $id ? 'nullable' : 'required';
        $request->validate([
            'name' => 'required|unique:locations,id,' . $id,
            'image' => [$imgRequired, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);

        if ($id) {
            $location = Location::findOrFail($id);
            $notify[] = ['success', 'Location updated successfully'];
        } else {
            $location = new Location();
            $notify[] = ['success', 'Location added successfully'];
        }

        if ($request->hasFile('image')) {
            try {
                $location->image = fileUploader($request->image, getFilePath('location'), getFileSize('location'), $id ? $location->image : null);
            } catch (\Exception $exp) {
                return returnBack('Couldn\'t upload your image');
            }
        }

        $location->name = $request->name;
        $location->slug = slug($request->name);
        $location->save();

        return back()->withNotify($notify);
    }

    public function featured($id) {
        $location              = Location::findOrFail($id);
        $location->is_featured = !$location->is_featured;
        $location->save();

        $notify[] = ['success', 'Location changed successfully'];
        return back()->withNotify($notify);
    }


    public function status($id) {
        return Location::changeStatus($id);
    }

    public function sortLocation() {
        Location::sortOrder();
    }
}

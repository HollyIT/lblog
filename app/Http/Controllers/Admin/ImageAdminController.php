<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImageSaveRequest;
use App\Models\Image;

class ImageAdminController
{
    public function index()
    {
        return view('admin.images.manage', [
            'pageTitle' => 'Images',
            'images' => Image::query()->with(['formats', 'user', 'posts'])->orderBy('updated_at', 'desc')->paginate(10),
            'sorted' => '-updated_at',
        ]);
    }

    public function create()
    {
        return view('admin.images.form', [
            'image' => new Image(),
        ]);
    }

    public function store(ImageSaveRequest $request)
    {
        $image = Image::createFromUpload($request->file('image'));

        return redirect(route('admin.images.edit', ['image' => $image]))->withSuccess("Image has been created");
    }

    public function edit(Image $image)
    {
        return view('admin.images.form', [
            'image' => $image,
        ]);
    }

    public function update(Image $image, ImageSaveRequest $request)
    {
        $image->attachUpload($request->file('image'));
        $image->save();

        return redirect(route('admin.images.index'))->withSuccess('Image has been updated');
    }

    public function destroy(Image $image)
    {
        $image->delete();

        return redirect(route('admin.images.index'))->withSuccess('Image delete');
    }
}

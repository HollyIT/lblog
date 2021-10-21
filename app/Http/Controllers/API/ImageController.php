<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ImageSaveRequest;
use App\Http\Resources\ImageResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Models\Image;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $query = QueryBuilder::for(Image::class)
            ->allowedFilters([AllowedFilter::exact('group')])
            ->defaultSort('updated_at')
            ->allowedSorts(['updated_at', 'created_at'])
            ->paginate();

        return ImageResource::collection($query);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ImageSaveRequest  $request
     * @return ImageResource
     */
    public function store(ImageSaveRequest $request): ImageResource
    {
        return new ImageResource(Image::createFromUpload($request->file('image')));
    }

    /**
     * Display the specified resource.
     *
     * @param  Image  $image
     * @return ImageResource
     */
    public function show(Image $image): ImageResource
    {
        return new ImageResource($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ImageSaveRequest  $request
     * @param  Image  $image
     * @return Response|ImageResource
     */
    public function update(ImageSaveRequest $request, Image $image): Response|ImageResource
    {
        $image->attachUpload($request->file('image'));
        $image->save();

        return new ImageResource($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Image  $image
     * @return ResourceDeletedResponse
     */
    public function destroy(Image $image): ResourceDeletedResponse
    {
        $image->delete();

        return new ResourceDeletedResponse();
    }
}

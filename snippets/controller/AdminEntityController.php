<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Intervention\Image\ImageManager;
use Carbon\Carbon;
use App\Models\Entity;

class AdminEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $entitys = Entity::latest()->with('image')->paginate(12);

      return view('admin.entity.index', compact('entitys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $entity = null;

      return view('admin.entity.create', compact('entity'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestEntity = $request->entity;
        if(isset($requestEntity->id)){
          $entity = Entity::findOrFail($requestEntity->id);
        } else {
          $entity = new Entity();
        }

        $entity->image_id = $request->pictureId;
        $entity->name = $requestEntity['title'];
        $entity->body = $requestEntity['body'];
        $entity->order = 0;
        $entity->save();

        return response()->json([
            'code' => 200,
        ]);
    }

    public function uploadImage(Request $request)
    {
        $images = $request->file('items');
        foreach ($images as $key => $image) {
          if ($image == null )
          {
            return response()->json([
              'error' => true
            ]);
          }
          $filename =  Carbon::now()->timestamp . '_' . ($image->getClientOriginalName());
          $path = public_path('images/entitys/' . $filename);

          $manager = new ImageManager();
          $savedImage= $manager->make($image->getRealPath())->resize(1200, 800)->save($path);

          $uploadedImage = new Image();
          $uploadedImage->name = $filename;
          $uploadedImage->alt = $filename;
          $uploadedImage->save();
        }


        return response()->json([
          'error' => false,
          'id' => $uploadedImage->id,
          'filename' => $filename
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Entity $entity)
    {
      return view('admin.entity.create', compact('entity'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entity $entity)
    {
        $entity->delete();

        return response()->json([
            'code' => 200,
        ]);
    }

    public function publish(Entity $entity)
    {
      $entity->isPublished = true;
      $entity->save();

      return response()->json([
          'code' => 200,
      ]);
    }

    public function unpublish(Entity $entity)
    {
      $entity->isPublished = false;
      $entity->save();

      return response()->json([
          'code' => 200
      ]);
    }
}

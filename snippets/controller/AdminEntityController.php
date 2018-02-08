<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Intervention\Image\ImageManager;
use Carbon\Carbon;
use App\Models\Entity;
use Maatwebsite\Excel\Facades\Excel;

class AdminEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->order)){
            $order = $request->order;
        } else {
            $order = 'created_at';
        }

        if(isset($request->order)){
            $direction = $request->direction;
        } else {
            $direction = 'DESC';
        }

        $entitys = Entity::orderBy($order, $direction)->with('image')->paginate(12);

        $entitys = $this->addRoutesToEntities($entitys);

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
        if(isset($requestEntity['id'])){
          $entity = Entity::findOrFail($requestEntity['id']);
        } else {
          $entity = new Entity();
        }

        $entity->image_id = $request->pictureId;
        $entity->title = $requestEntity['title'];
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
        $image = $entity->image;

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
        $entity->active = true;
        $entity->save();

        return response()->json([
            'code' => 200,
        ]);
    }

    public function unpublish(Entity $entity)
    {
        $entity->active = false;
        $entity->save();

        return response()->json([
            'code' => 200
        ]);
    }

    public function search(Request $request)
    {
        $query = $request['query'];

        if($query != ""){
            $results = Entity::where('title', 'like', '%' . $query . '%')->paginate(12);
        } else {
            $results = Entity::latest()->with('image')->paginate(12);
        }

        $results = collect($results->items());
        $results = $this->addRoutesToEntities($results);

        return response()->json([
            'code' => 200,
            'results' => $results,
        ]);
    }

    public function bulkAction(Request $request)
    {
        $action = $request['action'];
        $selections = $request['selections'];

        $entitys = Entity::whereIn('id', $selections)->get();

        switch($action){
            CASE 'unpublish':
                foreach ($entitys as $entity){
                    $entity->active = false;
                    $entity->save();
                }
                break;

            CASE 'publish':
                foreach ($entitys as $entity){
                    $entity->active = true;
                    $entity->save();
                }
                break;

            CASE 'delete':
                foreach ($entitys as $entity){
                    $entity->delete();
                }
                break;

            default:
                break;
        }

        $entitys = Entity::latest()->with('image')->paginate(12)->items();
        $entitys = $this->addRoutesToEntities($entitys);

        return response()->json([
            'code' => 200,
            'updated_results' => $entitys,
        ]);
    }

    public function export()
    {
        $entitys = Entity::all();

        return Excel::create('entity', function($excel) use ($entitys) {
            $excel->sheet('entity', function($sheet) use ($entitys) {
                $sheet->fromModel($entitys);
            });
        })->export('xls');
    }

    private function addRoutesToEntities($entitys)
    {
        foreach($entitys as $entity){
            $entity->route = [
                'show' => route('entity.show', ['entity' => $entity->id]),
                'edit' => route('entity.edit', ['entity' => $entity->id]),
                'publish' => route('entity.publish', ['entity' => $entity->id]),
                'unpublish' => route('entity.unpublish', ['entity' => $entity->id]),
                'delete' => route('entity.destroy', ['entity' => $entity->id])
            ];
        }

        return $entitys;
    }
}

<?php

namespace App\Http\Controllers;

use App\Traits\Exportable;
use App\Traits\Media;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Entity;
use App\Traits\Publishable;

/**
 * Class AdminEntityController
 *
 * @package App\Http\Controllers
 */

class AdminEntityController extends BaseController
{
    use Publishable;
    use Exportable;
    use Media;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request Illuminate\Http\Request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $order = 'created_at';
        if (isset($request->order)) {
            $order = $request->order;
        }

        $direction = 'DESC';
        if (isset($request->order)) {
            $direction = $request->direction;
        }

        $entitys = Entity::orderBy($order, $direction)
                            ->with($this->getEagerLoad())
                            ->paginate($this->getPagination());

        $route = route('entity.index') .
                        '?order=' . trim($order) .
                        '&direction=' . trim($direction);

        $entitys->withPath($route);

        $entitys = $this->_addRoutesToEntities($entitys);

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
     * @param Request $request Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $requestEntity = $request->entity;

        $validatedData = $this->_requestEntityValidation($request);

        if (isset($requestEntity['id'])) {
            $entity = Entity::findOrFail($requestEntity['id']);
            $entity->update($validatedData);
        }

        if (!isset($requestEntity['id'])) {
            $entity = Entity::create($validatedData);
        }

        return response()->json(
            [
            'code' => 200,
            ]
        );
    }

    /**
     * Upload a new image
     *
     * @param Request $request Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $images = $request->file('items');
        $uploadedImage = $this->multipleUpload($images, 'public', 'entitys');


        return response()->json($uploadedImage);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Entity $entity Route Model Binding
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Entity $entity)
    {
        $image = $entity->image;

        return view('admin.entity.create', compact('entity'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Entity $entity Route Model Binding
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function destroy(Entity $entity)
    {
        $entity->delete();

        return response()->json(
            [
            'code' => 200,
            ]
        );
    }

    /**
     * Filter items by search
     *
     * @param Request $request Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request['query'];

        $results = Entity::latest();
        if ($query != "") {
            $results = Entity::where('title', 'like', '%' . $query . '%');
        }

        $results = $results->with($this->getEagerLoad())->paginate($this->getPagination());

        $results = collect($results->items());
        $results = $this->_addRoutesToEntities($results);

        return response()->json(
            [
            'code' => 200,
            'results' => $results,
            ]
        );
    }

    /**
     * Apply actions to multiple units
     *
     * @param Request $request Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(Request $request)
    {
        $action = $request['action'];
        $selections = $request['selections'];

        $entitys = Entity::whereIn('id', $selections)->get();

        switch($action){
        CASE 'unpublish':
            foreach ($entitys as $entity) {
                $entity->active = false;
                $entity->save();
            }
            break;

        CASE 'publish':
            foreach ($entitys as $entity) {
                $entity->active = true;
                $entity->save();
            }
            break;

        CASE 'delete':
            foreach ($entitys as $entity) {
                $entity->delete();
            }
            break;

        default:
            break;
        }

        $entitys = Entity::latest()
                        ->with($this->getEagerLoad())
                        ->paginate($this->getPagination())
                        ->items();

        $entitys = $this->_addRoutesToEntities($entitys);

        return response()->json(
            [
            'code' => 200,
            'updated_results' => $entitys,
            ]
        );
    }

    /**
     * Export entity to csv file
     *
     * @return mixed
     */
    public function export()
    {
        $entitys = Entity::all();

        return $this->exportToCSVFromModel($entitys);
    }

    /**
     * Add routes to entity for front querying
     *
     * @param $entitys Entity
     *
     * @return mixed
     */
    private function _addRoutesToEntities($entitys)
    {
        foreach ($entitys as $entity) {
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

    /**
     * Validate data
     *
     * @param $request Request
     *
     * @return mixed
     */
    private function _requestEntityValidation($request)
    {
        $validatedData = $request->validate(
            [
            'entity.title' => 'required|min:5',
            'entity.body' => 'required',
            'pictureId' => 'required',
            ]
        );

        return $validatedData;
    }

    private function _getExportMappings()
    {
        return [
            'headerName' => 'databaseColumnName',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Entity;

class EntityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $entitys = Entity::latest()->active()->with('image')->paginate(12);

      return view('entity.index', compact('entitys'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $entity = Entity::where('slug', $slug)->with('image')->first();

        return view('entity.show', compact('entity'));
    }

}

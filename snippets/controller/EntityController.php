<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Carbon\Carbon;

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Entity $entity)
    {

        return view('admin.entity.show', compact('entity'));
    }

}

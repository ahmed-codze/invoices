<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.section', [
            'sections' => $sections
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'section_name'  => 'required|unique:sections,section_name|max:200',
            'description'   =>  'required:sections,description|max:500'
        ]);

        Section::create([
            'section_name' => $request['section_name'],
            'description'  => $request['description'],
            'created_by'   => Auth()->user()->name
        ]);
        session()->flash('add-success', 'تم اضافة القسم بنجاح');
        return redirect('/sections');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'section_name'  => 'required|max:200|unique:sections,section_name,' . $id,
            'description'   =>  'required:sections,description|max:500'
        ]);

        Section::where('id', $id)->update([
            'section_name' => $request->section_name,
            'description'  => $request->description
        ]);
        session()->flash('updating-success', 'تم تحيث القسم بنجاح');
        return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Section::where('id', $request->id)->delete();

        session()->flash('deleting-success', 'تم حذف القسم بنجاح');

        return redirect('/sections');
    }
}

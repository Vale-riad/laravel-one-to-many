<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Type;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        $types = Type::all();
        return view('admin.projects.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
         
        $data = $request->validated();
        $new_project = new Project();
        $new_project->fill($data);
        $new_project->slug = Str::slug($new_project->title);
        if ( isset($data['cover_image']) ) {
            $new_project->cover_image = Storage::disk('public')->put('uploads', $data['cover_image']);
        }
        $new_project->save();

        return redirect()->route('admin.projects.index')->with('message', "Il Progetto $new_project->title è stato creato con successo!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $project = Project::where('slug', $slug)->first();

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {    $types = Type::all();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        $old_title = $project->title;

        $project->slug = Str::slug($data['title']);
        if ( isset($data['cover_image']) ) {
            if( $project->cover_image ) {
                Storage::disk('public')->delete($project->cover_image);
            }
            $data['cover_image'] = Storage::disk('public')->put('uploads', $data['cover_image']);
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('message', "Il progetto $old_title è stato aggiornato!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $old_title = $project->title;
        if( $project->cover_image ) {
            Storage::disk('public')->delete($project->cover_image);
        }
        
        $project->delete();

        return redirect()->route('admin.projects.index')->with('message', "Il post $old_title è stato cancellato!");
    }
}

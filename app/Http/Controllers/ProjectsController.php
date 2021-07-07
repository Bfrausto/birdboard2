<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index',compact('projects'));
    }
    public function show(Project $project)
    {
        // if(auth()->user()->isNot( $project->owner_id)){
        // if(auth()->id()!= $project->owner_id){
        //     abort(403);
        // }
        $this->authorize('update',$project);

        return view('projects.show',compact('project'));

    }
    public function store()
    {
        //validate
        // $attributes['owner_id'] = auth()->id();
        $project=auth()->user()->projects()->create(request()->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'min:3'
        ]));
        //persist
        //redirect
        return redirect($project->path());
    }
    public function create()
    {
        return view('projects.create');
    }
    public function update(Project $project)
    {
        $this->authorize('update',$project);
        // if(auth()->user()->isNot($project->owner)){
        //     abort(403);
        // }
        $project->update( request(['notes']));

        return redirect($project->path());
    }
}

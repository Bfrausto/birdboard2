<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->accessibleProjects();

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
        $project=auth()->user()->projects()->create($this->validateRequest());
        //persist
        //redirect
        return redirect($project->path());
    }
    public function edit(Project $project)
    {
        return view('projects.edit',compact('project'));

    }

    public function create()
    {
        return view('projects.create');
    }
    public function update(UpdateProjectRequest $request)
    {
        return redirect($request->save()->path());

        // $this->authorize('update',$project);
        // if(auth()->user()->isNot($project->owner)){
        //     abort(403);
        // }

        // $project->update( $this->validateRequest());
        // $project->update( $request->validated());



    }
    public function destroy(Project $project)
    {
        $this->authorize('update',$project);

        $project->delete();
        return redirect('/projects');
    }
    public function validateRequest()
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);
    }
}

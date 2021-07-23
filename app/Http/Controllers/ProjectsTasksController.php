<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectsTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update',$project);

        // if(auth()->user()->isNot($project->owner)){
        //     abort(403);
        // }
        request()->validate(['body' => 'required']);
        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project,Task $task)
    {


        // if(auth()->user()->isNot($project->owner)){
        //     abort(403);
        // }
        // $task->update([
        //     'body'=> request('body'),
        //     'completed'=> request()->has('completed')
        // ]);

         // $method = request('completed')?'complete':'incomplete';

        // $task->$method();
        $this->authorize('update',$task->project);

        $task->update(request()->validate(['body' => 'required']));

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->path());
    }
}

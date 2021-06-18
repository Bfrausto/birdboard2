@extends('layouts.app')
@section('content')
    <header class="flex items-end mb-3 py-4">
        <div class="flex justify-between items-center w-full">
            <p  class="text-grey text-sm font-normal">
               <a href="/projects" class="text-grey text-sm font-normal no-underline">My Projects </a>/ {{$project->title}}
            </p>
            <a href="/projects/create" class="button">New Project</a>
        </div>
    </header>
    <main>
        <div class="lg:flex -m-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2  class="text-grey text-lg font-normal mb-3">Tasks</h2>
                    @foreach ($project->tasks as $task)

                        <div class="card mb-3">
                            <form action="{{$project->path().'/tasks/'.$task->id}}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input name='body' value="{{$task->body}}" class="w-full">
                                    <input name="completed" type="checkbox" onchange="this.form.submit()">
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3 ">
                        <form action="{{$project->path().'/tasks'}}" method="POST">
                            @csrf
                            <input placeholder='Ad a new task...' class="w-full" name="body">
                        </form>
                    </div>
                </div>
                <div>
                    <h2  class="text-grey text-lg font-normal mb-3">General Notes</h2>
                    <textarea class="card w-full" style="min-height: 200px">Lorem ipsum dolor sit amet, consectet</textarea>
                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                @include('projects.card')
            </div>
        </div>
    </main>
@endsection

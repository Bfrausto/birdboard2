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
                    <div class="card mb-3">Lorem ipsum dolor sit amet, consectet</div>
                    <div class="card mb-3">Lorem ipsum dolor sit amet, consectet</div>
                    <div class="card mb-3">Lorem ipsum dolor sit amet, consectet</div>
                    <div class="card mb-3">Lorem ipsum dolor sit amet, consectet</div>
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

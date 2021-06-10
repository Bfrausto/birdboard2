@extends('layouts.app')
@section('content')
<body>
    <div style="display:flex; align-items: center;">
        <h1 style="margin-right: auto; ">Birdboard</h1>
        <a href="/projects/create">Create a New Project</a>
    </div>

    <ul>

<div class="bg-white">
    @forelse ($projects as $project)


    <li>
        <a href="{{$project->path()}}">{{$project->title}}</a>
        <h1>{{Str::limit($project->description,200)}}</h1>
    </li>
@empty
    <li>No projects yet.</li>
@endforelse
</div>

    </ul>
@endsection

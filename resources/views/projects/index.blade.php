@extends('layouts.app')
@section('content')
    <div >
        <header class="flex items-end mb-3 py-4">
            <div class="flex justify-between items-center w-full">
                <h2  class="text-default text-sm font-normal">My Projects</h2>
                <a href="/projects/create" class="button" @click.prevent="$modal.show('new-project')">New Project</a>
            </div>
        </header>

        <main class="lg:flex lg:flex-wrap -mx-3">
            @forelse ($projects as $project)
                <div class="lg:w-1/3 px-3 pb-6">
                    @include('projects.card')
                </div>
            @empty
                <div>No projects yet.</div>
            @endforelse
        </main>
    </div>
    <new-project-modal></new-project-modal>
@endsection

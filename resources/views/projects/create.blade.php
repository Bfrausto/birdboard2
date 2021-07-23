@extends('layouts.app')
@section('content')
    <div class="lg:w-1/2 lg:mx-auto bg-white py-12 px-16 rounded shadow">
        <h1 class="text-2xl font-normal mb-10 text-center">Let's start something new</h1>

        <form method="POST"
                action="/projects"
                >
            @include('projects.form',[
                'project'=> new App\Models\Project,
                'buttonText'=> 'Create Project'
                ])
        </form>
    </div>
	{{-- <form method="POST" action="{{ url('/projects')}}"class="container" style="padding-top: 40px">
		@csrf

		<h1 class="heading">Create a Project</h1>

		<div class="field">
			<label class="label" for="title">Title</label>

			<div class="control">
				<input type="text" class="input" name="title" placeholder="Title">
			</div>
		</div>

		<div class="field">
			<label class="label" for="description">Description</label>

			<div class="control">
				<textarea name="description" class="textarea"></textarea>
			</div>
		</div>

		<div class="field">
			<div class="control">
				<button type="submit" class="button is-link">Create Project</button>
                <a href="/projects">cancel</a>
			</div>
		</div>
	</form> --}}

@endsection

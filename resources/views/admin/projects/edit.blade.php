@extends('layouts.admin')

@section('content')
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
  <div class="py-4">
    <h1>Modifica Progetto</h1>
    <div class="mt-4">
        <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Titolo</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Inserisci il titolo" value="{{ old('title', $project->title) }}">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenuto</label>
                <textarea class="form-control" id="content" name="content" rows="10" placeholder="Inserisci il contenuto">{{ old('content', $project->content) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="cover_image" class="form-label">Immagine</label>
                <img id="output" width="100" class="mb-2" @if($project->cover_image) src="{{asset("storage/$project->cover_image")}}" @endif/>
                <input type="file" class="form-control" id="title" name="cover_image" placeholder="Inserisci il titolo" value="{{ old('cover_image') }}">
            </div>
            <button type="submit" class="btn btn-warning">Modifica</button>
        </form>
    </div>
  </div>
</div>
@endsection
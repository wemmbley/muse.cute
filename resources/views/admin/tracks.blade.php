@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <button type="button" class="btn btn-primary add-track" data-bs-toggle="modal" data-bs-target="#addTrack">
                    Add New Track
                </button>
                @foreach($tracks as $track)
                    <div class="track-card">
                        <img class="track-card__image" src="{{ $track->image_url }}" alt="" width="150" height="150">
                        <div class="track-card__header">
                            <h2 class="track-card__title">{{ $track->title }}</h2>
                            <h3 class="track-card__name">{{ $track->name }}</h3>
                            <div class="delete-track bubble-icon bubble-top">
                                <i class="close fas fa-times"></i>
                            </div>
                            <div class="edit-track bubble-icon bubble-bottom">
                                <i class="edit fas fa-edit"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-center">
                    {{ $tracks }}
                </div>
            </div>
        </div>
    </div>
    @include('admin.forms.upload-track')
    @include('admin.modals.success-upload')
    @include('admin.modals.delete-track')
@endsection

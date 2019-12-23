@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header text-center">
                        Updating Role: '{{ $scheduleStatus->code }}'
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('submitUpdateStatusType', ['id' => $scheduleStatus->id]) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">Legend</label>

                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $scheduleStatus->code }}" readonly disabled>

                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

                                <div class="col-md-6">
                                    <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $scheduleStatus->description }}" required>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="weight" class="col-md-4 col-form-label text-md-right">Day Weight</label>

                                <div class="col-md-6">
                                    <input id="weight" type="text" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ $scheduleStatus->weight }}" required>

                                    @error('weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="comments" class="col-md-4 col-form-label text-md-right">Comments</label>

                                <div class="col-md-6">
                                    <input id="comments" type="text" class="form-control @error('comments') is-invalid @enderror" name="comments" value="{{ $scheduleStatus->comments }}">

                                    @error('comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="background_color" class="col-md-4 col-form-label text-md-right">Indicator Background Colour</label>

                                <div class="col-md-6">
                                    <input id="background_color" type="color" class="form-control @error('background_color') is-invalid @enderror" name="background_color" value="{{ $scheduleStatus->background_color }}">

                                    @error('background_color')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="text_color" class="col-md-4 col-form-label text-md-right">Indicator Text Colour</label>

                                <div class="col-md-6">
                                    <input id="text_color" type="color" class="form-control @error('text_color') is-invalid @enderror" name="text_color" value="{{ $scheduleStatus->text_color }}">

                                    @error('text_color')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4 text-right">
                                    <button type="submit" class="btn btn-success ">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
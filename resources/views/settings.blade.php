@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header text-center">
                        Settings
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateSettings') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="emails_on" class="col-md-4 col-form-label text-md-right">Emails Enabled</label>

                                <div class="col-md-6">
                                    <input id="emails_on" type="checkbox" class="form-control @error('emails_on') is-invalid @enderror" name="emails_on" @if (env('EMAILS_ON')) checked @endif>

                                    @error('emails_on')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="target_email" class="col-md-4 col-form-label text-md-right">Target Email</label>

                                <div class="col-md-6">
                                    <input id="target_email" type="text" class="form-control @error('target_email') is-invalid @enderror" name="target_email" value="{{ env('TARGET_EMAIL') }}" required>

                                    @error('target_email')
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

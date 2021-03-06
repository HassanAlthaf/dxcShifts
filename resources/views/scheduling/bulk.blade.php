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
                        Bulk Scheduling
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('submitBulkScheduler') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="employees" class="col-md-4 col-form-label text-md-right">Employees</label>

                                <div class="col-md-6">
                                    <input id="employees" type="text" class="form-control @if($errors->has('employees')) is-invalid @endif" name="employees" value="{{ old('employees') }}" required autofocus>

                                    @if ($errors->has('employees'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employees') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>

                                <div class="col-md-6">
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="">Select a Status...</option>
                                        @foreach (\App\Scheduling\ScheduleStatus::all() as $status)
                                        <option value="{{ $status->id }}">{{ $status->code }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="range" class="col-md-4 col-form-label text-md-right">Range</label>

                                <div class="col-md-6">
                                    <input id="range" type="text" class="form-control @error('range') is-invalid @enderror" name="range" value="{{ old('range') }}" required>

                                    @error('range')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="days" class="col-md-4 col-form-label text-md-right">Days of Week</label>

                                <div class="col-md-6">
                                    <div class="form-control @error('days') is-invalid @enderror">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="0" name="days[]">
                                            <label class="form-check-label" for="inlineCheckbox1">Sun</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="1" name="days[]">
                                            <label class="form-check-label" for="inlineCheckbox2">Mon</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="2" name="days[]">
                                            <label class="form-check-label" for="inlineCheckbox3">Tue</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="3" name="days[]">
                                            <label class="form-check-label" for="inlineCheckbox3">Wed</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="4" name="days[]">
                                            <label class="form-check-label" for="inlineCheckbox3">Thu</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="5" name="days[]">
                                            <label class="form-check-label" for="inlineCheckbox3">Fri</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="6" name="days[]">
                                            <label class="form-check-label" for="inlineCheckbox3">Sat</label>
                                        </div>
                                    </div>

                                    @error('days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4 text-right">
                                    <button type="submit" class="btn btn-success ">
                                        Create
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

@section("scripts-body")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('#employees').selectize({
            persist: false,
            maxItems: null,
            valueField: 'id',
            labelField: 'name',
            searchField: ['name'],
            options: {!! \App\Employees\Employee::all() !!},
            render: {
                item: function(item, escape) {
                    return '<div>' +
                        (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
                        '</div>';
                },
                option: function(item, escape) {
                    var label = item.name;

                    return '<div class="p-2">' +
                        '<span class="label">' + escape(label) + '</span>' +
                        '</div>';
                }
            }
        });

        $('#range').daterangepicker();
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@endsection

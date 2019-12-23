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
                        Clone Schedules
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('submitClone') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="target_employee" class="col-md-4 col-form-label text-md-right">Target Employee</label>

                                <div class="col-md-6">
                                    <input id="target_employee" type="text" class="form-control @if($errors->has('target_employee')) is-invalid @endif" name="target_employee" value="{{ old('target_employee') }}" required autofocus>

                                    @if ($errors->has('target_employee'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('target_employee') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="destination_employees" class="col-md-4 col-form-label text-md-right">Destination Employees</label>

                                <div class="col-md-6">
                                    <input id="destination_employees" type="text" class="form-control @if($errors->has('destination_employees')) is-invalid @endif" name="destination_employees" value="{{ old('destination_employees') }}" required autofocus>

                                    @if ($errors->has('destination_employees'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('destination_employees') }}</strong>
                                    </span>
                                    @endif
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

        $('#target_employee').selectize({
            persist: false,
            maxItems: 1,
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

        $('#destination_employees').selectize({
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

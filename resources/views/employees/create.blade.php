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
                        Create employee records
                    </div>

                    <div class="card-body">
                        @if (request()->query('count') !== null && request()->query('count') > 0)
                        <form method="POST" action="{{ route('storeEmployee') }}">
                            @csrf
                            <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <th>Phone #</th>
                                <th>Role</th>
                                <th>Shift</th>
                            </tr>
                            @for ($i = 0; $i < (request()->query('count')); $i++)
                                <tr>
                                    <td>

                                        <input type="text" class="form-control @error($i . '.name') is-invalid @enderror" name="employees[{{ $i }}][name]" value="{{ old("employees." . $i .  ".name") }}">
                                        @error($i . '.name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error($i . '.phone_number') is-invalid @enderror" name="employees[{{ $i }}][phone_number]" value="{{ old("employees." . $i . ".phone_number") }}">
                                        @error($i . '.phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </td>
                                    <td>
                                        <select class="form-control @error($i . '.role_id') is-invalid @enderror" name="employees[{{ $i }}][role_id]">
                                            <option value="">Please click here to select a role.</option>
                                            @foreach (\App\Employees\Role::all() as $index => $role)
                                                <option value="{{ $role->id }}" @if (old("employees." . $i . ".role_id") == $role->id) selected @endif>{{ $role->code }}</option>
                                            @endforeach
                                        </select>
                                        @error($i . '.role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </td>
                                    <td>
                                        <select class="form-control @error($i . '.shift_id') is-invalid @enderror" name="employees[{{ $i }}][shift_id]">
                                            <option value="">Please click here to select a shift.</option>
                                            @foreach (\App\Employees\Shift::all() as $index => $shift)
                                                <option value="{{ $shift->id }}" @if (old("employees." . $i . ".shift_id") == $shift->id) selected @endif>{{ $shift->code }}</option>
                                            @endforeach
                                        </select>
                                        @error($i . '.shift_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </td>
                                </tr>
                            @endfor
                        </table>


                            <div class="text-right">
                                <button type="submit" class="btn btn-success ">
                                    Create
                                </button>
                            </div>
                        </form>
                        @else
                        <form method="GET">
                            <div class="form-group row">
                                <label for="count" class="col-md-4 col-form-label text-md-right">How many users do you wish to create</label>

                                <div class="col-md-6">
                                    <input id="count" type="number" class="form-control" name="count" min="1" max="50" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4 text-right">
                                    <button type="submit" class="btn btn-success ">
                                        Go
                                    </button>
                                </div>
                            </div>

                        </form>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header text-center">
                        Import CSV
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('employeeCSV') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="csv" class="col-md-4 col-form-label text-md-right">CSV File</label>

                                <div class="col-md-6">
                                    <input id="csv" type="file" class="form-control @error('csv') is-invalid @enderror" name="csv" required autofocus>

                                    @error('csv')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4 text-right">
                                    <button type="submit" class="btn btn-success ">
                                        Go
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

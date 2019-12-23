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
                        {{ $employee->name }}'s Profile
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateEmployee', ['id' => $employee->id]) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $employee->name }}" required autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                                <div class="col-md-6">
                                    <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ $employee->phone_number }}" required>

                                    @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="role_id" class="col-md-4 col-form-label text-md-right">Role</label>

                                <div class="col-md-6">
                                    <select id="role_id" class="form-control @error('role_id') is-invalid @enderror" name="role_id">
                                        @foreach (\App\Employees\Role::all() as $index => $role)
                                            <option value="{{ $role->id }}" @if ($employee->role->id == $role->id) selected @endif>{{ $role->code }}</option>
                                        @endforeach
                                    </select>

                                    @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="shift_id" class="col-md-4 col-form-label text-md-right">Shift</label>

                                <div class="col-md-6">
                                    <select id="shift_id" class="form-control @error('shift_id') is-invalid @enderror" name="shift_id">
                                        @foreach (\App\Employees\Shift::all() as $index => $shift)
                                            <option value="{{ $shift->id }}" @if ($employee->shift->id == $shift->id) selected @endif>{{ $shift->code }}</option>
                                        @endforeach
                                    </select>

                                    @error('shift_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4 text-right">
                                    <a href="{{ route('deleteEmployee', ['id' => $employee->id]) }}" class="btn btn-danger">Delete Employee</a>
                                    <button type="submit" class="btn btn-success ">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-center">
                        <a href="#" id="previous-month"><ion-icon name="arrow-back" data-toggle="tooltip" data-placement="bottom" title="Previous Month"></ion-icon></a>

                        <span id="month-year-indicator"></span>

                        <a href="#" id="next-month"><ion-icon name="arrow-forward" data-toggle="tooltip" data-placement="bottom" title="Next Month"></ion-icon></a>
                    </div>
                </div>

                <div id="calendar" class="mt-3"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts-body')
    <script type="text/javascript" src="{{ asset('js/vexlendar.min.js') }}"></script>
    <script type="text/javascript">
        let employeeId = '{{ $employee->id }}';

        function statusChange(e) {
            e.preventDefault();
            let option = this.options[this.selectedIndex];

            this.style.backgroundColor = option.dataset['background'];
            this.style.color = option.dataset['text'];

            window.location.href = '/schedule/employees/{{ $employee->id }}?date=' + this.dataset.date + '&&month=' + this.dataset.month + '&&year=' + this.dataset.year + '&&scheduleStatus=' + option.value;
        }

        let allStatuses = JSON.parse('{!! \App\Scheduling\ScheduleFormatterFacade::getScheduleStatuses() !!}');
        let fullSchedule = JSON.parse('{!! \App\Scheduling\ScheduleFormatterFacade::getScheduleByEmployee($employee->id, $month, $year) !!}');

        function populateData(element, date, month, year) {
            console.log(element);
            let container = document.createElement('div');

            let field = document.createElement('select');
            field.setAttribute('name', 'status-' + date);
            field.classList.add('form-control');
            field.classList.add('select-status');
            field.dataset.date = date;
            field.dataset.month = month;
            field.dataset.year = year;

            let defaultOption = document.createElement('option');
            defaultOption.setAttribute('value', '-1');
            defaultOption.innerText = "Set Status";

            field.append(defaultOption);

            for (let i = 0; i < allStatuses.length; i++) {

                let status = document.createElement('option');
                status.setAttribute('value', allStatuses[i].id);
                status.dataset.background = allStatuses[i]['background_color'];
                status.dataset.text = allStatuses[i]['text_color'];

                status.innerText = allStatuses[i].code;

                if (fullSchedule[date] !== undefined && fullSchedule[date] !== null) {
                    console.log(fullSchedule[date]);
                    if (fullSchedule[date]['schedule_status_id'] === allStatuses[i].id) {
                        field.style.backgroundColor = allStatuses[i]['background_color'];
                        field.style.color = allStatuses[i]['text_color'];

                        status.setAttribute('selected', '');

                        defaultOption.innerText = "Remove Status";
                    }
                }

                field.append(status);
            }

            let dateLabel = document.createElement('span');
            dateLabel.innerText = date;

            field.addEventListener('change', this.statusChange);

            container.append(dateLabel);
            container.append(field);

            element.append(container);
        };

        let calendar = vexlender(
            document.getElementById('calendar'),
            {
                monthYearIndicator: document.getElementById('month-year-indicator'),
                month: '{{ $month }}',
                year: Number('{{ $year }}'),
                dateRenderer: function (element, date, month, year) {
                    this.populateData(element, date, month, year);
                },
                dateNodeClick: function (e, day, month, year) {

                },
                redirectFunction: function (month, year) {
                    window.location.href = '/employees/{{ $employee->id }}?month=' + month + '&&year=' + year;
                }
            }
        );

        document.getElementById('previous-month').addEventListener('click', function (e) {
            calendar.previousMonth();
        });

        document.getElementById('next-month').addEventListener('click', function (e) {
            calendar.nextMonth();
        });


    </script>
@endsection

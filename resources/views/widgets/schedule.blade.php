
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-center">
                <form class="form-inline justify-content-center">
                    <div class="form-group">

                        <input type="month" id="scheduleDate" name="start"
                               min="2018-01" value="{{ $year ?? date('Y') }}-{{ $month ?? date('m') }}"
                               max="{{ date('Y') + 1}}-12"
                               class="form-control">
                        <span class="ml-2">
                            <a href="#" id="expandSize"><ion-icon name="resize" data-toggle="tooltip" data-placement="bottom" title="Expand Columns"></ion-icon></a>
                            <a href="#" id="fitAll"><ion-icon name="grid" data-toggle="tooltip" data-placement="bottom" title="Shrink Columns to Fit"></ion-icon></a>
                            <a href="#" id="saveState"><ion-icon name="save" data-toggle="tooltip" data-placement="bottom" title="Save Employee Index Order"></ion-icon></a>
                            <a href="{{ route('schedulingReport') }}"><ion-icon name="pulse" data-toggle="tooltip" data-placement="bottom" title="View Scheduling Report"></ion-icon></a>

                        </span>
                    </div>
                </form>
            </div>


            <div class="card-body text-center">

                <a href="#" id="previousMonth"><ion-icon name="arrow-back" data-toggle="tooltip" data-placement="bottom" title="Previous Month"></ion-icon></a>

                Use the arrows to navigate by month.

                <a href="#" id="nextMonth"><ion-icon name="arrow-forward" data-toggle="tooltip" data-placement="bottom" title="Next Month"></ion-icon></a>

            </div>
        </div>
    </div>


</div>

<div id="scheduleGrid" class="ag-theme-balham w-100 mt-4" style="height: 500px;"></div>

@section('scripts-head')
    <script src="https://unpkg.com/@ag-grid-community/all-modules/dist/ag-grid-community.min.js"></script>
@endsection

@section('scripts-body')
    <script type="text/javascript">
        var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        var month = Number("{{ $month ?? date('m') }}");
        var year = Number("{{ $year ?? date('Y') }}");
        var daysInMonth = new Date(year, month, 0).getDate();

        var previousMonthButton = document.getElementById('previousMonth');
        var nextMonthButton = document.getElementById('nextMonth');

        previousMonthButton.addEventListener('click', function (e) {
            e.preventDefault();

            if (month === 1) {
                year--;
                month = 12;
            } else {
                month--;
            }

            if (month < 10)
                month = '0' + month;

            window.location.href = "/schedules/" + year + "/" + month;
        });

        nextMonthButton.addEventListener('click', function (e) {
            e.preventDefault();

            if (month === 12) {
                year++;
                month = 1;
            } else {
                month++;
            }

            if (month < 10)
                month = '0' + month;

            window.location.href = "/schedules/" + year + "/" + month;
        });

        function linkNameToProfile(params) {
            return '<a href="/employees/' + params.data.id + '">' + params.value + '</a>'
        }

        var columnDefs = [
            {
                headerName: "Employee Information",
                children: [
                    {headerName: "Employee ID", field: "id", resizable: true, rowDrag: true},
                    {headerName: "Name", field: "name", sortable: true, resizable: true, cellRenderer: linkNameToProfile},
                    {headerName: "Role", field: "role_code", sortable: true, resizable: true},
                    {headerName: "Phone #", field: "phone_number", sortable: true, resizable: true},
                    {headerName: "Shift", field: "shift_code", sortable: true, resizable: true}
                ],
            }
        ];

        for (var day = 1; day < (daysInMonth + 1); day++) {
            var currentDate = new Date(year + '-' + month + '-' + day);

            columnDefs.push({
                headerName: days[currentDate.getDay()],
                headerGroupComponent: ColumnCellRenderer,
                children: [{
                    headerName: day,
                    field: 'day-' + day,
                    resizable: true,
                    cellRenderer: ScheduleCellRenderer
                }],
            });
        }

        var rowData = {!! \App\Scheduling\ScheduleFormatterFacade::getSchedule($year ?? 0, $month ?? 0) !!};

        var gridOptions = {
            columnDefs: columnDefs,
            rowData: rowData,
            onFirstDataRendered: function(params) {

                var allColumnIds = [];
                gridOptions.columnApi.getAllColumns().forEach(function(column) {
                    allColumnIds.push(column.colId);
                });
                gridOptions.columnApi.autoSizeColumns(allColumnIds);
            },
            animateRows: true,
            onRowDragMove: onRowDragMove,
            getRowNodeId: getRowNodeId,
            onSortChanged: onSortChanged,
            onFilterChanged: onFilterChanged
        };

        var sortActive = false;
        var filterActive = false;

        function onSortChanged() {
            var sortModel = gridOptions.api.getSortModel();
            sortActive = sortModel && sortModel.length > 0;

            var suppressRowDrag = sortActive || filterActive;
            console.log('sortActive = ' + sortActive
                + ', filterActive = ' + filterActive
                + ', allowRowDrag = ' + suppressRowDrag);
            gridOptions.api.setSuppressRowDrag(suppressRowDrag);
        }


        function onFilterChanged() {
            filterActive = gridOptions.api.isAnyFilterPresent();

            var suppressRowDrag = sortActive || filterActive;
            console.log('sortActive = ' + sortActive
                + ', filterActive = ' + filterActive
                + ', allowRowDrag = ' + suppressRowDrag);
            gridOptions.api.setSuppressRowDrag(suppressRowDrag);
        }


        function getRowNodeId(data) {
            console.log(data);
            return data.order;
        }

        function onRowDragMove(event) {
            var movingNode = event.node;
            var overNode = event.overNode;

            var rowNeedsToMove = (movingNode !== overNode) && (!overNode !== true);

            if (rowNeedsToMove) {
                var movingData = movingNode.data;
                var overData = overNode.data;

                var fromIndex = rowData.indexOf(movingData);
                var toIndex = rowData.indexOf(overData);

                var newStore = rowData.slice();
                moveInArray(newStore, fromIndex, toIndex);

                rowData = newStore;
                gridOptions.api.setRowData(newStore);

                gridOptions.api.clearFocusedCell();
            }

            function moveInArray(arr, fromIndex, toIndex) {
                var element = arr[fromIndex];

                let temp = arr[fromIndex].order;

                arr[fromIndex].order = arr[toIndex].order;
                arr[toIndex].order = temp;

                arr.splice(fromIndex, 1);
                arr.splice(toIndex, 0, element);
            }
        }



        function sizeToFit(e) {
            e.preventDefault();
            gridOptions.api.sizeColumnsToFit();
        }

        function autoSizeAll(e) {
            e.preventDefault();

            var allColumnIds = [];
            gridOptions.columnApi.getAllColumns().forEach(function(column) {
                allColumnIds.push(column.colId);
            });
            gridOptions.columnApi.autoSizeColumns(allColumnIds);
        }

        document.getElementById('expandSize').addEventListener('click', autoSizeAll);
        document.getElementById('fitAll').addEventListener('click', sizeToFit);

        document.getElementById('scheduleDate').addEventListener('input', function (e) {
            var date = this.value.split('-');

            window.location.href = "/schedules/" + date[0] + "/" + date[1];
        });

        function ScheduleCellRenderer(params) {
            let value = params.value || '';

            if (value.length > 0) {
                let fieldName = params.colDef.field;

                params.eGridCell.style.backgroundColor = params.data[fieldName + '-background'];
                params.eGridCell.style.color = params.data[fieldName + '-text'];
            }

            return value;

        }

        let holidays = {!! json_encode(\App\Http\Controllers\Scheduling\HolidaysController::getHolidays($month ?? 0, $year ?? 0))  !!};

        function ColumnCellRenderer () {}

        let count = 1;

        ColumnCellRenderer.prototype.init = function (params) {
            this.eGui = document.createElement('span');

            if (params.displayName !== "Sat" && params.displayName !== "Sun") {
                this.eGui.classList.add('text-success');
                this.eGui.classList.add('font-weight-bold');
            }

            if (holidays[count] !== undefined && holidays[count] !== null) {
                this.eGui.classList.add('text-danger');

                if (!this.eGui.classList.contains('font-weight-bold')) {
                    this.eGui.classList.add('font-weight-bold');
                }
            }


            this.eGui.innerHTML = params.displayName || '';

            count++;
        };

        ColumnCellRenderer.prototype.getGui = function () {
            return this.eGui;
        };

        document.addEventListener('DOMContentLoaded', function() {
            var gridDiv = document.querySelector('#scheduleGrid');
            var gridObject = new agGrid.Grid(gridDiv, gridOptions);

            document.getElementById('saveState').addEventListener('click', function (e) {
                e.preventDefault();

                var suppressRowDrag = sortActive || filterActive;

                if (suppressRowDrag) {
                    alert("You cannot save the state of the table when any kind of sorting is active.");
                }

                let newIndexes = [];

                for (let i = 0; i < gridObject.gridOptions.rowData.length; i++) {
                    newIndexes[i] = {
                        id: gridObject.gridOptions.rowData[i].id,
                        order: gridObject.gridOptions.rowData[i].order
                    }

                }

                $.post("{{ route('updateEmployeeOrder') }}", {
                    data: newIndexes,
                    "_token": "{{ csrf_token() }}"
                }, function (e) {
                    alert("You have successfully updated the orders!");
                });
            });
        });


    </script>
@endsection

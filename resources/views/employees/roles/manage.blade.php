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
                        Roles Management
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ route('createRole') }}"><ion-icon name="add" data-toggle="tooltip" data-placement="bottom" title="Add a Role"></ion-icon></a>
                        <a href="#" id="expandSize"><ion-icon name="resize" data-toggle="tooltip" data-placement="bottom" title="Expand Columns"></ion-icon></a>
                        <a href="#" id="fitAll"><ion-icon name="grid" data-toggle="tooltip" data-placement="bottom" title="Shrink Columns to Fit"></ion-icon></a>
                    </div>
                </div>

                <div id="rolesTable" class="ag-theme-balham w-100 mt-4" style="height: 500px"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts-head')
    <script src="https://unpkg.com/@ag-grid-community/all-modules/dist/ag-grid-community.min.js"></script>
@endsection

@section('scripts-body')
    <script type="text/javascript">
        var columnDefs = [
            {
                headerName: "Legend", field: "code", sortable: true, resizable: true
            },
            {
                headerName: "Description", field: "description", sortable: true, resizable: true
            },
            {
                headerName: "Actions", field: "id", sortable: false, resizable: true, cellRenderer: function (params) {
                    return '<a href="/roles/' + params.data.id + '/update" class="text-success"><ion-icon name="create"></ion-icon></a>'
                        + '<a href="/roles/' + params.data.id + '/delete" class="text-danger"><ion-icon name="trash"></ion-icon></a>'
                }
            }
        ];

        var rowData = {!! $roles !!}

        var gridOptions = {
                columnDefs: columnDefs,
                rowData: rowData,
                onFirstDataRendered: function(params) {
                    params.api.sizeColumnsToFit();
                }
            };

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

        document.addEventListener('DOMContentLoaded', function() {
            var gridDiv = document.querySelector('#rolesTable');
            new agGrid.Grid(gridDiv, gridOptions);
        });
    </script>
@endsection
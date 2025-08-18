@extends('master') @section('header')
<div class="row">
    <div class="col-sm-6">
        <h3 class="mb-0">Locations</h3>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="/home">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Locations
            </li>
        </ol>
    </div>
</div>
@endsection @section('content') @if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
    ></button>
</div>
@endif
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Locations</h3>
            <a href="locations/form" class="btn btn-primary btn-sm"
                >Add Location</a
            >
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($locations) > 0) @forEach($locations as $key =>
                $location)
                <tr class="align-middle">
                    <td>{{ $key + 1 }}</td>
                    <td>{{$location->name}}</td>
                    <td>
                        {{$location->latitude}}
                    </td>
                    <td>{{$location->longitude}}</td>
                    <td>
                        <div class="btn-group">
                            <button
                                type="button"
                                class="btn btn-sm btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/locations/{{$location->id}}"
                                        >Edit</a
                                    >
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item btn-delete-location"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="{{ $location->id }}"
                                        data-name="{{ $location->name }}"
                                    >
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach @else
                <tr>
                    <td colspan="5" class="text-center">No locations found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div
        class="modal fade"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="deleteModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <form method="POST" id="deleteLocationForm">
                @csrf @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">
                            Delete Location
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete
                        <strong id="locationName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-location');
        console.log(deleteButtons, 'deleteButtons');
        const form = document.getElementById('deleteLocationForm');
        const locationName = document.getElementById('locationName');

        deleteButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                form.action = `/locations/${id}`;
                locationName.textContent = name;
            });
        });
    });
</script>
@endsection

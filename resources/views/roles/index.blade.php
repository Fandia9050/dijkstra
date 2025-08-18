@extends('master') @section('header')
<div class="row">
    <div class="col-sm-6">
        <h3 class="mb-0">Roles</h3>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="/home">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
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
            <h3 class="card-title">Roles</h3>
            <button
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#addRole"
            >
                Add Roles
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th style="width: 40px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($roles) > 0) @forEach($roles as $key => $role)
                <tr class="align-middle">
                    <td>{{ $key + 1 }}</td>
                    <td>{{$role->name}}</td>
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
                                        class="dropdown-item btn-edit-role"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editRole"
                                        data-id="{{ $role->id }}"
                                        data-name="{{ $role->name }}"
                                        >Edit</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="/roles/permissions?id={{$role->id}}"
                                        class="dropdown-item"
                                        >Assign Permissions</a
                                    >
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item btn-delete-location"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="{{ $role->id }}"
                                        data-name="{{ $role->name }}"
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
                    <td colspan="5" class="text-center">No roles found</td>
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

    <div
        class="modal fade"
        id="addRole"
        tabindex="-1"
        aria-labelledby="addRoleLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <form method="POST" action="/roles">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoleLabel">Add Roles</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                required
                            />
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div
        class="modal fade"
        id="editRole"
        tabindex="-1"
        aria-labelledby="editRoleLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <form method="POST" id="editRoleForm">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoleLabel">
                            Edit Roles
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name-edit"
                                name="name"
                                required
                            />
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            save
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
        const editButtons = document.querySelectorAll('.btn-edit-role');
        const formEdit = document.getElementById('editRoleForm');
        const inputName = document.getElementById('name-edit');

        editButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                formEdit.action = `/roles/${id}`;
                inputName.value = name;
            });
        });
        const form = document.getElementById('deleteLocationForm');
        const locationName = document.getElementById('locationName');

        deleteButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                form.action = `/roles/${id}`;
                locationName.textContent = name;
            });
        });
    });
</script>
@endsection

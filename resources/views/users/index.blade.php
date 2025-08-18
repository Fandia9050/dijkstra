@extends('master') @section('css')
<style>
    #map {
        height: 500px;
        width: 100%;
    }

    .select2-search__field {
        width: 100% !important;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}" />
@endSection @section('header')
<div class="row">
    <div class="col-sm-6">
        <h3 class="mb-0">Users</h3>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="/home">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">User</li>
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
            <h3 class="card-title">Users</h3>
            <button
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#addRole"
            >
                Add User
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
                    <th>Role</th>
                    <th style="width: 40px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($users) > 0) @forEach($users as $key => $user)
                <tr class="align-middle">
                    <td>{{ $key + 1 }}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->role_name ?? "-"}}</td>
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
                                        data-bs-target="#editUser"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        >Edit</a
                                    >
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item btn-assign-role"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignRole"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                    >
                                        Assign Role
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item btn-delete-location"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
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
                    <td colspan="5" class="text-center">No users found</td>
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
            <form method="POST" action="/users">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoleLabel">Add User</h5>
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
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                            />
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div>
                            <label for="password" class="form-label"
                                >Password</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
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
        id="assignRole"
        tabindex="-1"
        aria-labelledby="assignRoleLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <form method="POST" action="/assign-role" id="assignRoleForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignRoleLabel">
                            Assign Role
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <input type="hidden" name="user_id" id="assign-user-id" />
                    <div class="modal-body">
                        <div>
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="assign-username"
                                name="name"
                                disabled
                            />
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Role</label>
                            <select
                                id="roles"
                                name="role_id"
                                class="form-control select2bs4"
                                style="width: 100%"
                            ></select>
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
        id="editUser"
        tabindex="-1"
        aria-labelledby="editUserLable"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <form method="POST" id="editUserForm">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserLable">
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
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email-edit"
                                name="email"
                                required
                            />
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div>
                            <label for="password" class="form-label"
                                >Password</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                id="password-edit"
                                name="password"
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
@endsection @section("js")
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#roles').select2({
            dropdownParent: $('#assignRole'),
            placeholder: 'search role...',
            ajax: {
                url: '/roles',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    return {
                        results: data.roles.map(function (item) {
                            return { id: item.id, text: item.name };
                        }),
                    };
                },
            },
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-location');
        const editButtons = document.querySelectorAll('.btn-edit-role');
        const assignRoleButtons = document.querySelectorAll('.btn-assign-role');
        const formAssign = document.getElementById('assignRoleForm');
        const inputUsername = document.getElementById('assign-username');
        const formEdit = document.getElementById('editUserForm');
        const inputName = document.getElementById('name-edit');
        const inputEmail = document.getElementById('email-edit');
        const inputUserId = document.getElementById('assign-user-id');

        assignRoleButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                inputUsername.value = name;
                inputUserId.value = id;
            });
        });

        editButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                formEdit.action = `/users/${id}`;
                inputName.value = name;
                inputEmail.value = email;
            });
        });
        const form = document.getElementById('deleteLocationForm');
        const locationName = document.getElementById('locationName');

        deleteButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                form.action = `/users/${id}`;
                locationName.textContent = name;
            });
        });
    });
</script>

@endsection

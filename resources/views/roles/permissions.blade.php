@extends('master') @section('header')
<div class="row">
    <div class="col-sm-6">
        <h3 class="mb-0">Assign Permissions</h3>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="/home">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/roles">Roles</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Permissions
            </li>
        </ol>
    </div>
</div>
@endsection @section('content')
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
                    <th>Permissions</th>
                </tr>
            </thead>
            <tbody>
                @php $index = 0; @endphp @if(count($permissions) > 0)
                @forEach($permissions as $key => $permission) @php $index++;
                @endphp
                <tr class="align-middle">
                    <td>{{ $index }}</td>
                    <td>{{ $key }}</td>
                    <td>
                        @foreach($permission as $key => $permissionItem)
                        <div class="form-check">
                            <input
                                class="form-check-input permission-checkbox"
                                type="checkbox"
                                data-role-id="{{ $role->id ?? 1 }}"
                                data-permission-id="{{ $permissionItem->id }}"
                                id="permission-{{$permissionItem->id}}"
                                @checked($permissionItem->assigned) />
                            <label
                                class="form-check-label"
                                for="permission-{{$permissionItem->id}}"
                            >
                                {{$permissionItem->name}}
                            </label>
                        </div>
                        @endforeach
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
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.permission-checkbox').on('change', function () {
            let roleId = $(this).data('role-id');
            let permissionId = $(this).data('permission-id');
            let checked = $(this).is(':checked');

            $.ajax({
                url: "{{ route('assign.permission') }}",
                type: 'POST',
                data: {
                    role_id: roleId,
                    permission_id: permissionId,
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.message) {
                        toastr.success(response.message);
                    }
                },
                error: function (xhr) {
                    toastr.error('Something went wrong!');
                },
            });
        });
    });
</script>
@endsection

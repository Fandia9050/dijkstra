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
@endsection @section('content') @if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="col-md-12">
    <div class="card card-info card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">Form Locations</div>
        </div>
        <!--end::Header-->
        <!--begin::Form-->
        <form
            class="needs-validation"
            method="post"
            action="{{ $deliveryLocation->exists 
                ? '/locations/' . $deliveryLocation->id
                : '/locations' }}"
            method="POST"
            novalidate
        >
            @csrf @if ($deliveryLocation->exists) @method('PUT') @endif
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Row-->
                <div class="row g-3">
                    <!--begin::Col-->
                    <div>
                        <label for="name" class="form-label"
                            >Location Name</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            value="{{ old('name', $deliveryLocation->name) }}"
                            required
                        />
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div>
                        <label for="latitude" class="form-label"
                            >Latitude</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="latitude"
                            name="latitude"
                            value="{{ old('latitude', $deliveryLocation->latitude) }}"
                            required
                        />
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div>
                        <label for="name" class="form-label">Longitude</label>
                        <input
                            type="text"
                            class="form-control"
                            id="longitude"
                            name="longitude"
                            value="{{ old('longitude', $deliveryLocation->longitude) }}"
                            required
                        />
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
                <button class="btn btn-sm btn-primary" type="submit">
                    Submit form
                </button>
            </div>
            <!--end::Footer-->
        </form>
        <!--end::Form-->
        <!--begin::JavaScript-->
        <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (() => {
                'use strict';

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                const forms = document.querySelectorAll('.needs-validation');

                // Loop over them and prevent submission
                Array.from(forms).forEach((form) => {
                    form.addEventListener(
                        'submit',
                        (event) => {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopPropagation();
                            }

                            form.classList.add('was-validated');
                        },
                        false
                    );
                });
            })();
        </script>
        <!--end::JavaScript-->
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

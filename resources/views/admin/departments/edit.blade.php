@extends('admin.layout.master')

@section('title')
Department
@endsection

@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl mb-4">
    <form class="form" action="{{ route('admin.departments.update', ['department' => $department->id]) }}" method="post">
        @csrf
        <input name="_method" type="hidden" value="PATCH" />

        {{--            <div class="card-header border-0 pt-6 pb-6">
                <label class="fs-6 fw-semibold mb-2">{{__('Status')}}</label>
                <select class="form-control" name="status" required>
                    <option value="">...</option>
                    @foreach(StatusEnum::cases() as $status)
                    <option value="{{$status->value}}" {{$user->status == $status->value ? 'selected' : ''}}>{{$status->probertyName()}}</option>
                    @endforeach
                </select>
            </div>--}}
        <div class="row">
            <div class="card card-flush py-10">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-17">
{{--                    <div class="fv-row mb-7">--}}
{{--                        <label class="fs-6 fw-semibold mb-2">Name AR</label>--}}
{{--                        <input type="text" class="form-control" name="name_ar" value="{{ $department->name_ar }}"/>--}}
{{--                        @if($errors->has('name_ar'))--}}
{{--                            <span class="text-danger">{{$errors->first('name_ar')}}</span>--}}
{{--                        @endif--}}
{{--                    </div>--}}

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Name EN</label>
                        <input type="text" class="form-control" name="name_en" value="{{ $department->name_en }}"/>
                        @if($errors->has('name_en'))
                            <span class="text-danger">{{$errors->first('name_en')}}</span>
                        @endif
                    </div>
                </div>
                <!--end::Modal body-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Submit</span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
        </div>

    </form>
</div>
<!--end::Content container-->
{{--
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <div class="row">
        <!-- left card -->
        <div class="col-md-9">
            <div class="card card-flush py-10">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Phone</label>
                        <input type="text" class="form-control disabled" value="{{ $user->phone_number }}" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Email</label>
                        <input type="text" class="form-control disabled" value="{{ $user->email }}" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Full Name</label>
                        <input type="text" class="form-control disabled" value="{{ $user->full_name }}" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">State</label>
                        <input type="text" class="form-control disabled" value="{{ $user->city_id }}" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">City</label>
                        <input type="text" class="form-control disabled" value="{{ $user->city_id }}" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">License Type</label>
                        <input type="text" class="form-control disabled" value="{{ $user->licence_type }}" />
                    </div>
                </div>
                <!--end::Modal body-->
            </div>
        </div>
        <!-- right card -->
        <div class="col-md-3">
            <div class="card card-flush py-10">
                <div class="mb-7 px-lg-17">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <label class="fs-6 fw-semibold mb-2">{{__('Attachments')}}</label>
                </div>
                <div class="modal-body px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">License</label>
                        <span class="attachment">
                            @if($user->licenseAttachment->path)
                            <a href="{{$user->licenseAttachment->path}}" target="_blank"><i class="fa fa-file"></i></a>
                            @else
                            <i class="fa fa-close"></i>
                            @endif
                        </span>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Legal Establishment</label>
                        <span class="attachment">
                            @if($user->legalEstablishmentAttachment->path)
                            <a href="{{$user->legalEstablishmentAttachment->path}}" target="_blank"><i class="fa fa-file"></i></a>
                            @else
                            <i class="fa fa-close"></i>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Content container-->--}}
@endsection

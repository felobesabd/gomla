@extends('admin.layout.master')

@section('title')
Employees
@endsection

@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <form class="form" action="{{ route('admin.departments.store') }}" method="post">
        @csrf
        <div class="row">
            <div class="card card-flush py-10">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-17">
{{--                    <div class="fv-row mb-7">--}}
{{--                        <label class="fs-6 fw-semibold mb-2">Role</label>--}}
{{--                        <select class="form-control" name="freelancer_id" required>--}}
{{--                            <option value="">...</option>--}}
{{--                            @foreach(\App\Models\Freelancer::all() as $freelancer)--}}
{{--                            <option value="{{$freelancer->id}}">{{$freelancer->name_en}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <div class="fv-row mb-7">--}}
{{--                        <label class="fs-6 fw-semibold mb-2">Name AR</label>--}}
{{--                        <input type="text" class="form-control" name="name_ar" value="{{ old('name_ar') }}"/>--}}
{{--                        @if($errors->has('name_ar'))--}}
{{--                            <span class="text-danger">{{$errors->first('name_ar')}}</span>--}}
{{--                        @endif--}}
{{--                    </div>--}}

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Name</label>
                        <input type="text" class="form-control" name="name_en" value="{{ old('name_en') }}"/>
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
@endsection

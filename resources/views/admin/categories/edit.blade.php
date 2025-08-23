@extends('admin.layout.master')

@section('title')
Categories
@endsection

@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <form class="form" action="{{ route('admin.categories.update', ['category' => $category->id]) }}" method="post">
        @csrf
        <input name="_method" type="hidden" value="PATCH" />
        <div class="row">
            <div class="card card-flush py-10">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Category Name</label>
                        <input type="text" class="form-control" name="category_name" value="{{ $category->category_name }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Group</label>
                        <select class="form-control" name="group_id">
                            @foreach($groups as $group)
                                <option
                                    @if($category->group_id === $group->id) selected @endif value="{{ $group->id }}">
                                    {{ $group->group_name }}
                                </option>
                            @endforeach
                        </select>
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

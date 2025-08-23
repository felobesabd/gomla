@if ($message = Session::get('success'))
<div class="alert alert-success alert-block message" style="z-index: 99999;">
	<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block message">
	<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($message = \Illuminate\Http\Request::capture()->get('error'))
<div class="alert alert-danger alert-block message">
	<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($messages = Session::get('warnings'))
    <div class="alert alert-warning alert-block message">
        <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
        <ul>
            @foreach ($messages as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block message">
	<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-block message">
	<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
	{{$errors->first()}}
</div>
@endif

@if ($errors = Session::get('ex'))
    <div class="alert alert-danger alert-block message">
        <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert"></button>
        @if(is_array($errors))
            <ul>
                @foreach ($errors as $error)
                    @foreach ($error as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                @endforeach
            </ul>
        @else
            <strong>{{ $errors }}</strong>
        @endif
    </div>
@endif



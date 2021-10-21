@if (Session::has('success'))
    <div class="py-3 px-5 m-4 bg-blue-100 text-blue-900 text-sm rounded-md border border-blue-200"
         role="alert">
        {{ Session::get('success') }}
    </div>
@endif
@if (session('errors'))
    <div class="py-3 px-5 m-4 bg-red-100 text-red-900 text-sm rounded-md border border-red-200"
         role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

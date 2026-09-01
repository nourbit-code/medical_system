@if (session('success'))
    <div class="alert alert-success border-0">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger border-0">
        {{ session('error') }}
    </div>
@endif

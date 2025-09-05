@if(session('success'))
    <div class="alert alert-success">
        <span class="alert-icon">✅</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <span class="alert-icon">❌</span>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <span class="alert-icon">⚠️</span>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
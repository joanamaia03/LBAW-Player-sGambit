@if(session('error'))
    <h3 class="error">{{ session('error') }}</h3>
@elseif(session('success'))
    <h3 class="success">{{ session('success') }}</h3>
@endif  
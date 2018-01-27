<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.head')
</head>
<body>
<div id="app">
    @include('layouts.nav')
    <div class="container">
        @yield('content')
    </div>
    @include('layouts.footer')
</div>
<script>
    $(document).ready(function(){
        @yield('script')
    });
</script>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
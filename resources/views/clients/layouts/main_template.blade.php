<!doctype html>
<html lang="en">
<head>
    @include('clients.layouts.partial._head')
</head>
<body>
    <header>
        @include('clients.layouts.partial._header')
    </header>
    <main id="app">
        @yield('content')
    </main>
    @include('clients.layouts.partial._script')
</body>
</html>

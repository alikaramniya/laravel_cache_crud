<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>
        @section('title') welcome page @show
    </title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" media="screen" title="no title"
        charset="utf-8">
</head>
<body>
    @yield('content')
</body>
</html>

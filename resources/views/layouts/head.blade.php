<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

<!-- Styles -->

<link rel="stylesheet" href="{{ mix('css/app.css') }}">

<script src = "{{ URL::asset('js/jquery-3.1.1.min.js') }}" type = "text/javascript"></script>
<script src = "{{ URL::asset('js/bootstrap.min.js') }}" type = "text/javascript"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css"/>
<!-- Include Quill stylesheet -->
<link rel="stylesheet" type="text/css" href="https://cdn.quilljs.com/1.0.0/quill.snow.css">


<!-- facebook open graph -->
<meta property="og:title" content="Meta" />
<meta property="og:type" content="article" />
<meta property="og:url" content="{{url('/')}}" />
<meta property="og:image" content="{{url('#'}" />


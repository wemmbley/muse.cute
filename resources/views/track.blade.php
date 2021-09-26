<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('fonts/gilroy.css') }}">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @if($page_theme === 'light')
        <link rel="stylesheet" href="{{ asset('css/social-dark.css') }}">
    @endif
</head>
<body>
<div class="background-image" style="background: url({{ $image_url }}) no-repeat center center fixed;"></div>
<div class="social">
    <div class="cover">
        <div class="social-bar">
            @foreach($social_links as $site => $link)
                @if(is_null($link))
                    @continue
                @endif
                <a href="{{ $link }}" class="social-bar__card {{ $site }}">
                    <img class="icon-24" src="{{ asset('images/social/'.$site.'.svg') }}" alt="{{ $site }}">
                    <p>{{ $site }}</p>
                </a>
            @endforeach
        </div>
        <img class="cover__image" src="{{ $image_url }}" alt="" width="300" height="300">
        <div class="cover__label">
            <h2 class="cover__header">{{ $title }}</h2>
            <h3 class="cover__desc">{{ $name }}</h3>
        </div>
    </div>
</div>
</body>
</html>

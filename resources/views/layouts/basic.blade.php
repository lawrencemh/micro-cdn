<html>
<head>
    <title>@yield('title', 'CDN')</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" rel="stylesheet">
    <style>
        body {
            background-color: {{ array_random(['#27ae60', '#2980b9', '#c0392b', '#2c3e50']) }};
            display: flex;
            font-family: 'Lato', sans-serif;
        }

        .flex-container {
            display: flex;
            height: 100%;
            width: 100%;
            justify-content: center;
            align-items: center;
            background: inherit;
        }

        .col div {
            height: 40px;
            max-width: 700px;
            text-align: center;
            line-height: 75px;
            margin-bottom: 25px;
        }
        h1, h2 {
            color: white;
            text-align: center;
            margin: 0;
            line-height: 40px;
        }

        h1 {
            font-weight: 100;
            font-size: 80px;
            text-transform: uppercase;
        }

        h2 {
            font-weight: 300;
            font-size: 35px;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>

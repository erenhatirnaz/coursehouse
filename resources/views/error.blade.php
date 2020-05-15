<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
    <head>
        <base href="{{ url('/') }}/" />
        <title>{{ $message }} - {{ config('app.name') }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;900&display=swap" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
                margin-bottom: -25px;
                font-weight: bold;
            }

            .message {
                font-size: 50px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{ $code }}</div>
                <div class="message">{{ $message }}</div>
                <a class="btn btn-success" href="{{ url('/') }}">Go to Home Page</a>
            </div>
        </div>
    </body>
</html>

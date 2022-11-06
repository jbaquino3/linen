<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Linen Inventory System</title>

    <style>
        body {
            font-family: "Arial", "Courier New", "Calibri";
        }
    </style>
</head>
<body>
    <div id="app">
        @if (isset($token))
            <App :token="{{json_encode($token)}}"></App>
        @else
            <App></App>
        @endif
    </div>

    <script src="{{ mix('/js/main.js') }}" defer></script>
</body>
</html>
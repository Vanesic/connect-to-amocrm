<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Отправка формы</title>
    @vite( ['resources/css/bootstrap.css', 'resources/js/bootstrap.js'])
    <style>
        body {
            background: rgb(230, 255, 194);
            background: linear-gradient(62deg, rgba(230, 255, 194, 0.9246870623249299) 0%, rgba(105, 214, 83, 0.636171656162465) 35%, rgba(27, 69, 14, 0.9190848214285714) 100%);
        }
    </style>
</head>
<body>
<div class="vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-4">
        <div class="border border-3 border-success"></div>
        <div class="card  bg-white shadow p-5">
            <div class="mb-4 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="75" height="75"
                     fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path
                        d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                </svg>
            </div>
            <div class="text-center">
                <h1>Спасибо!</h1>
                <p>Форма отпрвлена</p>
                <a href="{{ url('/') }}" class="btn btn-outline-success">Вернуться</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>

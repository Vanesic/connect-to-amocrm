<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Отправка формы</title>
    @vite( ['resources/css/bootstrap.css', 'resources/js/bootstrap.js'])
    <style>
        section {
            background: rgb(60, 211, 241);
            background: linear-gradient(62deg, rgba(60, 211, 241, 0.9246870623249299) 0%, rgba(35, 125, 219, 1) 35%, rgba(12, 18, 56, 1) 100%);
        }
    </style>
</head>
<body>
<section class="vh-100">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card rounded-5">
                        <div class="card-body p-5">
                            <h2 class="text-uppercase text-center mb-5">Форма для отправки</h2>
                            <form action="{{ url('/send') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" name="name" class="form-control form-control-lg"/>
                                    <label class="form-label" for="name">Имя</label>
                                </div>

                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="email" name="email" class="form-control form-control-lg"/>
                                    <label class="form-label" for="email">Email</label>
                                </div>

                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="tel" name="phone" class="form-control form-control-lg"/>
                                    <label class="form-label" for="phone">Номер телефона</label>
                                </div>
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" name="price" class="form-control form-control-lg"/>
                                    <label class="form-label" for="price">Цена</label>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit"
                                            class="btn btn-outline-primary btn-block btn-lg text-body">
                                        Отправить
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>

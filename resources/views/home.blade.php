<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Livewire Crud</title>
    <link data-minify="1" href="https://www.bacancytechnology.com/blog/wp-content/cache/min/1/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css?ver=1672663261" rel="stylesheet" crossorigin="anonymous">
    @livewireStyles
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Livewire CRUD Blog</a>
        </div>
    </nav>
    <div class="container">
        <div class="row justify-content-center mt-3">
            @livewire('post')
        </div>
    </div>
 
    @livewireScripts
  </body>
</html>
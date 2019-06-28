<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1,minimal-ui" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Material+Icons">
    <link rel="stylesheet" href="https://unpkg.com/vue-material@beta/dist/vue-material.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-material@beta/dist/theme/default.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <title>Image Uploader API</title>
  </head>

  <body>
    <div id="app">
      <home-component></home-component>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>

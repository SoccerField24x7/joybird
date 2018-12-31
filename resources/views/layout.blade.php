<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-30
 * Time: 11:14
 */
?>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/joybird.css" />

</head>
<body>
    <div class="container">
        <div class="row spacer-top">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 font-weight-bold text-center">
                        Hello Joybird!
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="/joybird">DataTables Report</a>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="/chart">Chart</a>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>
    <div class="container-fluid footer">
        <div class="row spacer-bottom">
            <div class="col-md-12 text-center">
                <div class="row">
                    <div class="col-md-4">
                        <a href="#">Jesse on LinkedIn</a>
                    </div>
                    <div class="col-md-4">
                        <a href="#">Jesse on Exercism.io</a>
                    </div>
                    <div class="col-md-4">
                        <a href="#">Jesse on GitHub</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    @yield('javascript')
</body>
</html>

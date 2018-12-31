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
    <style>
        #dtable {
            table-layout: fixed;
            width: 100% !important;
        }
        #dtable td,
        #dtable th{
            width: auto !important;
            white-space: normal;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        td {
            font-size: 12px;
        }
        th {
            font-size: 13px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />

</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    @yield('javascript')
</body>
</html>

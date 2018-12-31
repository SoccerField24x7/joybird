<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-26
 * Time: 19:13
 */

?>
@extends('layout')
@section('title')Joybird: DataTables Test @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <table id="dtable" class="display">
                <thead>
                    <tr>
                        <th>Bought Number</th>
                        <th>Bought Item</th>
                        <th>Sold Number</th>
                        <th>Sold Item</th>
                        <th>Default Purchase Price</th>
                        <th>Container Unit pPrice</th>
                        <th>Avg Sale Price (JAQ)</th>
                        <th>Total Purchased</th>
                        <th>Vendors</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#dtable').DataTable( {
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: '/report',
                    type: 'POST'
                },
                searching: false,
                "order": [[ 7, "desc" ]]
            });
        });
    </script>
@stop

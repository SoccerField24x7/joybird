<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-30
 * Time: 11:12
 */
?>
@extends('layout')
@section('title')Joybird: HighCharts Test @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="chart"></div>
        </div>
    </div>
@stop
@section('javascript')
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            getChartData().then(buildChart);
        });

        function getChartData() {
            const prom = new Promise(function(resolve, reject) {
                $.ajax({
                    url: '/chartdata',
                    data: {
                        format: 'json'
                    },
                    error: function(err) {
                        reject(err)
                    },
                    //dataType: 'jsonp',
                    success: function (data) {
                        let obj = JSON.parse(data);

                        //fulfill promise
                        resolve(obj);
                    },
                    type: 'POST'
                });
            });

            return prom;
        }

        function buildChart(data) {
            let vendors = Object.keys(data);
            let values = Object.values(data);

            Highcharts.chart('chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total Purchased by Vendor'
                },
                subtitle: {
                    text: 'Confidential Business Data'
                },
                xAxis: {
                    categories: vendors,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Number of Units'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Total',
                    data: values,
                }]
            });

        }

    </script>
@stop

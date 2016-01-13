@extends('layout')

@section('content')                
                <div class="row">
                    <div class="col-md-12" id="chart-1">
                        
                    </div>
                </div>
@endsection

@section('js')
    <script src="{{ url('js/highcharts.js') }}"></script>
    <script src="{{ url('js/modules/exporting.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#chart-1').highcharts({
                title: {
                    text: 'Pencapaian Target',
                    x: -20 //center
                },
                xAxis: {
                    categories: {!! json_encode($months) !!}
                },
                yAxis: {
                    title: {
                        text: 'Total (ribu rupiah)'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: '000'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: {!! json_encode($data) !!}
            });
        });
    </script>
@endsection

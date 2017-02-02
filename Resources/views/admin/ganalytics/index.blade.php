@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('contact::contacts.title.contacts') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('ganalytic::ganalytics.title.ganalytics') }}</li>
    </ol>
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Monthly Google Analytics</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <a type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-wrench"></i></a>

                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">
                            <strong>{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</strong>
                        </p>

                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="salesChart" style="height: 180px; width: 633px;" height="180" width="633"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            {{--<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>--}}
                            <h5 class="description-header" id="total_visitor">0</h5>
                            <span class="description-text">TOTAL VISITOR</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            {{--<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>--}}
                            <h5 class="description-header" id="total_new_visitor">0</h5>
                            <span class="description-text">TOTAL NEW VISITOR</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            {{--<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>--}}
                            <h5 class="description-header" id="total_pageview">0</h5>
                            <span class="description-text">TOTAL PAGEVIEW</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block">
                            {{--<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>--}}
                            <h5 class="description-header" id="bounce_rate">0</h5>
                            <span class="description-text">BOUNCE RATE</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
        <div class="box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">Google Analytics Setting</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: none;">
                {!! Form::open(['url' => route('admin.ganalytic.store'), 'method' => 'post','enctype' => 'multipart/form-data']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class='form-group{{ $errors->has('view_id') ? ' has-error' : '' }}'>
                            {!! Form::label('view_id', trans('ganalytic::ganalytics.form.view id')) !!}
                            {!! Form::text('view_id', config("laravel-analytics.view_id"), ['class' => 'form-control', 'placeholder' => trans('ganalytic::ganalytics.form.view id')]) !!}

                            {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                        </div>
                        <div class='form-group{{ $errors->has('service_account_credentials_json') ? ' has-error' : '' }}'>
                            {!! Form::label('service_account_credentials_json', trans('ganalytic::ganalytics.form.json_fil.e')) !!}
                            {!! Form::file('service_account_credentials_json', Input::old('service_account_credentials_json'), ['class' => 'form-control', 'placeholder' => trans('ganalytic::ganalytics.form.json_file')]) !!}
                            <input type="text" class="form-control" readonly value="{{config("laravel-analytics.service_account_credentials_json")}}">
                            {!! $errors->first('service_account_credentials_json', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="box box-primary">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                                <button class="btn btn-default btn-flat" name="button" type="reset">{{ trans('core::core.button.reset') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                    {!! Form::close() !!}
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- ./box-body -->

        </div>

    </div>

@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('contact::contacts.title.create contact') }}</dd>
    </dl>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {


            $.ajax({
                type: 'GET',
                url: "{{ route('admin.ganalytic.show','default') }}",
                success: function (data) {

                    $('#total_visitor').html(data.total[0]);
                    $('#total_new_visitor').html(data.total[1]);
                    $('#total_pageview').html(data.total[2]);
                    $('#bounce_rate').html(data.total[3]);
                    // Get context with jQuery - using jQuery's .get() method.
                    var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
                    // This will get the first returned node in the jQuery collection.
                    var salesChart = new Chart(salesChartCanvas);

                    var salesChartOptions = {
                        //Boolean - If we should show the scale at all
                        showScale: true,
                        //Boolean - Whether grid lines are shown across the chart
                        scaleShowGridLines: false,
                        //String - Colour of the grid lines
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        //Number - Width of the grid lines
                        scaleGridLineWidth: 1,
                        //Boolean - Whether to show horizontal lines (except X axis)
                        scaleShowHorizontalLines: true,
                        //Boolean - Whether to show vertical lines (except Y axis)
                        scaleShowVerticalLines: true,
                        //Boolean - Whether the line is curved between points
                        bezierCurve: true,
                        //Number - Tension of the bezier curve between points
                        bezierCurveTension: 0.3,
                        //Boolean - Whether to show a dot for each point
                        pointDot: false,
                        //Number - Radius of each point dot in pixels
                        pointDotRadius: 4,
                        //Number - Pixel width of point dot stroke
                        pointDotStrokeWidth: 1,
                        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                        pointHitDetectionRadius: 20,
                        //Boolean - Whether to show a stroke for datasets
                        datasetStroke: true,
                        //Number - Pixel width of dataset stroke
                        datasetStrokeWidth: 2,
                        //Boolean - Whether to fill the dataset with a color
                        datasetFill: false,
                        //String - A legend template
                        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"><%if(datasets[i].label){%><%=datasets[i].label%><%}%></span></li><%}%></ul>",
                        //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                        maintainAspectRatio: true,
                        //Boolean - whether to make the chart responsive to window resizing
                        responsive: true
                    };

                    //Create the line chart
                    console.log(data);
                    salesChart.Line(data, salesChartOptions);
                }
            });

            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.contact.contact.create') ?>" }
                ]
            });
        });
    </script>
    <?php $locale = locale(); ?>
    <script type="text/javascript">
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
@stop

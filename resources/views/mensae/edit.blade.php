@extends('base')

@section('styles')
    @parent
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <form method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="title">Titel:</label>
                                <input id="title" name="title" value="{{ old('title', $mensa->title) }}" class="form-control"  />
                            </div>
                            <div class="form-group">
                                <label for="date">Datum:</label>
                                <div class="input-group date" id="datepicker">
                                    <input id="date" name="date" class="form-control" />
                                    <label for="date" class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="closing_time">Sluittijd:</label>
                                <div class="input-group date">
                                    <input id="closing_time" name="closing_time" class="form-control" />
                                    <label for="closing_time" class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="max_users">Max inschrijvingen:</label>
                                <input id="max_users" type="number" name="max_users" value="{{ old('max_users', $mensa->max_users) }}" class="form-control"  />
                            </div>

                            <input type="submit" value="Submit" class="btn btn-primary" />&nbsp;&nbsp;<a href="{{ route('home') }}" class="btn btn-default">Terug</a>
                        </form>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            moment.locale('en', {
                                week: { dow: 1 } // Monday is the first day of the week
                            });

                            $('#date').datetimepicker({
                                format: 'DD-MM-YYYY HH:mm',
                                minDate: moment().set('hour', 0).set('minute', 0),
                                defaultDate: moment().set('hour', 19).set('minute', 0)
                            });
                            $('#closing_time').datetimepicker({
                                format: 'DD-MM-YYYY HH:mm',
                                minDate: moment().set('hour', 0).set('minute', 0),
                                defaultDate: moment().set('hour', 16).set('minute', 0),
                                stepping: 5
                            });

                            $("#date").on("dp.change", function (e) {
                                var datePicker = $('#closing_time').data("DateTimePicker");
                                var date = datePicker.date();
                                date.set('year', e.date.get('year')).set('month', e.date.get('month')).set('date', e.date.get('date'));
                                datePicker.date(date);
                            });

                            $("#closing_time").on("dp.change", function (e) {
                                var datePicker = $("#date").data("DateTimePicker");
                                if(e.date.unix() <= datePicker.date().unix())
                                    return;

                                var date = datePicker.date();
                                date.set('year', e.date.get('year')).set('month', e.date.get('month')).set('date', e.date.get('date'));
                                datePicker.date(date);
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection

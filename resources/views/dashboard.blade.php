@extends("common.layout")

@section("body")
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Page Header
                <small>Optional description</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            <div id="chart"></div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@endsection

@section('scripts')
    <script src="{{ asset ("/bower_components/d3/d3.min.js") }}"></script>
    <script src="{{ elixir('js/d3.js') }}"></script>
@stop
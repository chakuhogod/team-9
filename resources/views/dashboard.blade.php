@extends("common.layout")

@section("body")
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <section id="content" class="animated fadeIn">

                <div class="cover cover-lucid">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="panel">
                                <div class="pn pl20 p5">
                                    <h2 class="mt15 lh15" id="current-balance">&nbsp;</h2>
                                    <h5 class="text-muted">Balance</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Your Page Content Here -->
            <div id="graph-day"></div>
                <h4>Transactions per month</h4>
            <div id="chart"></div>
                <h4> Purchase & sales per month</h4>
            <div id="chart1"></div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@endsection

@section('scripts')
    <script src="{{ asset ("/bower_components/d3/d3.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/c3/c3.min.js") }}"></script>
    <script src="{{ 'js/c3.js'}}"></script>
@stop
@extends("common.layout")

@section("body")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Charts
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container content-container">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="search-filters">
                            <form id="searchForm" action="#" method="POST" class="sidebar-form">
                                <div class="input-group">
                                    <input name="beginPeriod" id="beginPeriod" class="form-control" type="date">
                                    <span class="input-group-btn">
                                        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="search-filters">
                            <form id="searchForm" action="#" method="POST" class="sidebar-form">
                                <div class="input-group">
                                    <input name="endPeriod" id="endPeriod" class="form-control" type="date">
                                    <span class="input-group-btn">
                                        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop
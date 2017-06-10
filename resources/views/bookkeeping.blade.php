@extends("common.layout")

@section("body")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Manual bookkeeping
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="search-filters">
                    <form id="searchForm" action="#" method="POST" class="sidebar-form">
                        <div class="input-group">
                            <input name="search" class="form-control" placeholder="Search..." type="text">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </form>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop
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
                <form id="searchForm" action="" method="GET" class="sidebar-form">
                    <div class="input-group">
                        <input name="search" id="search" class="form-control" placeholder="Search..." type="text">
                        <span class="input-group-btn">
                            <button type="submit" id="search-btn" class="btn btn-flat">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>

            <?php

                if(isset($search)) {
                    echo "<h2>Displaying search results for '" . $search . "'</h2>";
                }

            ?>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop
<?php 
include 'functions.php';
?>
<?=headersiswa('home','Joewandewa','blank.png')?>
<!-- Sidebar Navigation Start -->
<div class="sidebar--nav">
                <ul>
                    <li>
                        <ul>
                            <li >
                                <a href="index-siswa.php">
                                    <i class="fa fa-home"></i>
                                    <span>Home</span>
                                </a>
                            </li>
                            <li>
                                <a href="biodata-siswa.php">
                                    <i class="fa fa-users"></i>
                                    <span>Biodata</span>
                                </a>
                            </li>
                            <li >
                                <a href="screening-list-siswa.php">
                                    <i class="fa fa-pencil-alt"></i>
                                    <span>Screening</span>
                                </a>
                            </li>
                            <li class="active">
                                <a href="rapor-siswa.php">
                                    <i class="fa fa-file"></i>
                                    <span>Rapor</span>
                                </a>
                            </li>
                            <li>
                                <a href="profile-siswa.php">
                                    <i class="fa fa-user"></i>
                                    <span>Akun</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>
                                <a href="logout.php">
                                    <i class="fa fa-power-off"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- Sidebar Navigation End -->

        </aside>
        <!-- Sidebar End -->
        <main class="main--container">
        <section class="main--content">
                <div class="row gutter-20">
                <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">Rapor</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-siswa.php">Home</a></li>
                                <li class="breadcrumb-item active"><span>Rapor</span></li>
                            </ul>
                        </div>
                <div class="col-xl-12">
                            <div class="profile--panel">
                                    <div class="img online">
                                        <img src="assets/img/avatars/01_150x150.png" alt="" class="rounded-circle">
                                    </div>

                                <div class="name">
                                    <h3 class="h3">Henry Foster</h3>
                                </div>

                                <div class="role">
                                    <p style="color:#fff ;">20102249</p>
                                </div>

                                <div class="bio">
                                    <p style="color:#fff ;">Institut Teknolohi Telkom Purwokerto</p>
                                </div>

                                <div class="panel-subtitle">
                                    <h5 class="h5">Kelas</h5>
                                </div>

                                <!-- Tabs Nav Start -->
                                <ul class="nav nav-tabs nav-tabs-line" align="center">
                                    <li class="nav-item">
                                        <a href="#tab07" data-toggle="tab" class="nav-link active">Kelas 10</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tab08" data-toggle="tab" class="nav-link">Kelas 11</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tab09" data-toggle="tab" class="nav-link">Kelas 12</a>
                                    </li>
                                </ul>
                                <!-- Tabs Nav End -->

                                <!-- Tab Content Start -->
                                <div class="tab-content">
                                    <!-- Tab Pane Start -->
                                    <div class="tab-pane fade show active" id="tab07">
                                    <div class="panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Sales Progress</h3>

                                                <div class="dropdown">
                                                    <button type="button" class="btn-link dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">This Week</a></li>
                                                        <li><a href="#">Last Week</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="panel-chart">
                                                <!-- Morris Line Chart 01 Start -->
                                                <div id="morrisLineChart01" class="chart--body line--chart style--1"></div>
                                                <!-- Morris Line Chart 01 End -->
                                            </div>
                                        </div>
                                        <p style="color:#fff ;">Detail disini</p>
                                    </div>
                                    <!-- Tab Pane End -->

                                    <!-- Tab Pane Start -->
                                    <div class="tab-pane fade" id="tab08">
                                    <div class="panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Sales Progress</h3>

                                                <div class="dropdown">
                                                    <button type="button" class="btn-link dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">This Week</a></li>
                                                        <li><a href="#">Last Week</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="panel-chart">
                                                <!-- Morris Line Chart 01 Start -->
                                                <div id="morrisLineChart01" class="chart--body line--chart style--1"></div>
                                                <!-- Morris Line Chart 01 End -->
                                            </div>
                                        </div>
                                        <p style="color:#fff ;">Detail disini</p>
                                    </div>
                                    <!-- Tab Pane End -->

                                    <!-- Tab Pane Start -->
                                    <div class="tab-pane fade" id="tab09">
                                    <div class="panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Sales Progress</h3>

                                                <div class="dropdown">
                                                    <button type="button" class="btn-link dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">This Week</a></li>
                                                        <li><a href="#">Last Week</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="panel-chart">
                                                <!-- Morris Line Chart 01 Start -->
                                                <div id="morrisLineChart01" class="chart--body line--chart style--1"></div>
                                                <!-- Morris Line Chart 01 End -->
                                            </div>
                                        </div>
                                        <p style="color:#fff ;">Detail disini</p>
                                    </div>
                                    <!-- Tab Pane End -->
                                </div>
                                <!-- Tab Content End -->
                            </div>
                    </div>

                    </div>
        </section>
        </main>
<?=footersiswa()?>
<?php 
include 'functions.php';
?>
<?=headersiswa('home','Joewandewa','blank.png')?>
<!-- Sidebar Navigation Start -->
<style>
        ul {
  list-style: none;
  display: table;
}

li {
  display: table-row;
}

b {
  display: table-cell;
  padding-right: 1em;
}
</style>
<div class="sidebar--nav">
                <ul>
                    <li>
                        <ul>
                            <li class="active">
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
                                <a href="dass42-siswa.php">
                                    <i class="fa fa-pencil-alt"></i>
                                    <span>Screening DASS 42</span>
                                </a>
                            </li>
                            <li>
                                <a href="sgb-siswa.php">
                                    <i class="fa fa-pencil-alt"></i>
                                    <span>Screening Gaya Belajar</span>
                                </a>
                            </li>
                            <li>
                                <a href="sp-siswa.php">
                                    <i class="fa fa-pencil-alt"></i>
                                    <span>Screening Pengetahuan</span>
                                </a>
                            </li>
                            <li>
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
        <section class="page--header">
                <div class="container-fluid">
                    <div class="row">
                <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">Screening Gaya Belajar Siswa</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-siswa.php">Home</a></li>
                                <li class="breadcrumb-item active"><span>Gaya Belajar</span></li>
                            </ul>
                        </div>

                    </div>
                </div>
        </section>

        <section class="main--content">

        <div class="panel-heading">
            <h5 style="color:#009378;">Gaya belajar siswa</h5><br>           
        </div>
                    <div class="panel-content">
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Peran perempuan dan laki-laki di masyarakat tidak tetap dan dapat diubah.</span>
                                        <div class="col-md-10">
                                        <label class="form-check">
                                            <input type="radio" name="checkbox" value="1" class="form-check-input" checked>
                                            <span class="form-check-label">Cepat</span>
                                        </label>

                                        <label class="form-check">
                                            <input type="radio" name="checkbox" value="2" class="form-check-input">
                                            <span class="form-check-label">Berirama</span>
                                        </label>

                                        <label class="form-check">
                                            <input type="radio" name="checkbox" value="2" class="form-check-input">
                                            <span class="form-check-label">Lambat</span>
                                        </label>
                                    </div>
                                    </label>
                                </div>
                                <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
                    </div>
            </section>
        </main>
<?=footersiswa()?>
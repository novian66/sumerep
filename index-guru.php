<?php 
session_start();
include('functions.php');
$pdo = pdo_connect_mysql();
if(!isset($_SESSION['guru_id'])){
    header('location:index.php');
 }

 $ids = $_SESSION['guru_id'];
$stmt = $pdo->prepare("SELECT user_guru.*,sekolah.nama AS nama_sekolah
FROM user_guru
JOIN sekolah ON user_guru.id_sekolah = sekolah.id where user_guru.user_id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nama = $row['nama_lengkap'];
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
?>
<?=headersiswa('home',"$nama",$foto,'guru')?>
<!-- Sidebar Navigation Start -->
<div class="sidebar--nav">
                <ul>
                    <li>
                    <ul>
                            <li class="active">
                                <a href="index-guru.php">
                                    <i class="fa fa-home"></i>
                                    <span>Home</span>
                                </a>
                            </li>
                            <li>
                                <a href="dashboard-guru.php">
                                    <i class="fa fa-file-archive"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li >
                                <a href="data-siswa-guru.php">
                                    <i class="fa fa-user-plus"></i>
                                    <span>Data Siswa</span>
                                </a>
                            </li>
                            <li>
                                <a href="screening-gurus.php">
                                    <i class="fa fa-pencil-alt"></i>
                                    <span>Screening</span>
                                </a>
                            </li>
                            <li>
                                <a href="profile-guru.php">
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

                <div class="col-xl-12">
                <div class="profile--panel">
                    <div class="img-wrapper">
                        <div class="img online">
                        <img src="assets/img/avatars/01_150x150.png" alt="" class="rounded-circle">
                        </div>
                    </div>

                    <div class="name">
                        <h3 class="h3"><?=$nama?></h3>
                    </div>

                    <div class="role">
                        <p style="color:#fff;"><?=$row['nama_sekolah']?></p>
                    </div>

                    <div class="info">
                        <div class="button-group">
                            <div class="row">
                            <div class="col">
                                <a href="dashboard-guru.php" class="btn btn-rounded btn-default"><i class="fa fa-file-archive"></i><br>Dashboard</a>
                            </div>
                            <div class="col">
                                <a href="data-siswa-guru.php" class="btn btn-rounded btn-default"><i class="fa fa-user-plus"></i><br>Data Siswa</a>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col">
                                <a href="screening-gurus.php" class="btn btn-rounded btn-default"><i class="fa fa-pencil-alt"></i><br>Screening</a>
                            </div>
                            <div class="col">
                                <a href="profile-guru.php" class="btn btn-rounded btn-default"><i class="fa fa-user"></i><br>Akun</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>


                </div>
        </section>
        </main>
<?=footersiswa()?>
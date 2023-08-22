<?php 
session_start();
include('functions.php');
$pdo = pdo_connect_mysql();
if(!isset($_SESSION['siswa_id'])){
    header('location:index.php');
 }

 $ids = $_SESSION['siswa_id'];
$stmt = $pdo->prepare("SELECT user_students.*,sekolah.nama AS nama_sekolah
FROM user_students
JOIN sekolah ON user_students.id_sekolah = sekolah.id where user_students.user_id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nama = $row['nama_lengkap'];

?>
<?=headersiswa('home',"$nama",'blank.png')?>
<!-- Sidebar Navigation Start -->
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
                                <a href="screening-list-siswa.php">
                                    <i class="fa fa-pencil-alt"></i>
                                    <span>Screening</span>
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
                                <a href="biodata-siswa.php" class="btn btn-rounded btn-default"><i class="fa fa-users"></i><br>Biodata</a>
                            </div>
                            <div class="col">
                                <a href="rapor-siswa.php" class="btn btn-rounded btn-default"><i class="fa fa-file"></i><br>Rapor</a>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col">
                                <a href="screening-list-siswa.php" class="btn btn-rounded btn-default"><i class="fa fa-pencil-alt"></i><br>Screening</a>
                            </div>
                            <div class="col">
                                <a href="profile-siswa.php" class="btn btn-rounded btn-default"><i class="fa fa-user"></i><br>Akun</a>
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
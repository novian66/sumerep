<?php 
session_start();
include('functions.php');
$pdo = pdo_connect_mysql();
if(!isset($_SESSION['guru_id'])){
    header('location:index.php');
 }

 $ids = $_SESSION['guru_id'];
 $id_kelas = $_GET['kelas'];
$stmt = $pdo->prepare("SELECT user_guru.*,sekolah.nama AS nama_sekolah
FROM user_guru
JOIN sekolah ON user_guru.id_sekolah = sekolah.id where user_guru.user_id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nama = $row['nama_lengkap'];
$id_sekolah = $row['id_sekolah'];

$stmt2s = $pdo->prepare("SELECT kelas FROM kelas WHERE id = '$id_kelas'");
$stmt2s->execute();
// Fetch the records so we can display them in our template.
$row2 = $stmt2s->fetch(PDO::FETCH_ASSOC);
$kelas = $row2['kelas'];

$stmt2 = $pdo->prepare("SELECT * FROM user_students WHERE id_sekolah = '$id_sekolah' and id_kelas = '$id_kelas'");
$stmt2->execute();
// Fetch the records so we can display them in our template.
$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<?=headersiswa('List Siswa',"$nama",'blank.png')?>
<!-- Sidebar Navigation Start -->
<div class="sidebar--nav">
                <ul>
                    <li>
                    <ul>
                            <li>
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
                            <li class="active">
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

                <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">List Siswa <?=$kelas?></h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-guru.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="data-siswa-guru.php">Data Siswa</a></li>
                                <li class="breadcrumb-item active"><span>List Siswa <?=$kelas?></span></li>
                            </ul>
                        </div>

                <div class="col-xl-12">
                <div class="profile--panel">
                    
                <div class="card mb-2">
  <div class="card-body text-left table-responsive">
    <table class="table table-borderless" id="table">
      <thead>
        <tr>
          <th scope="col">Nama</th>
          <th scope="col">Kelas</th>
          <th scope="col">Screening 1</th>
          <th scope="col">Screening 2</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach($rows2 as $row){ 
        $user_id = $row['id'];
        $num = $pdo->query(" SELECT COUNT(*) FROM check_done WHERE user_id = '$user_id' and type = 'mandiri'")->fetchColumn();
        $num2 = $pdo->query(" SELECT COUNT(*) FROM check_done WHERE user_id = '$user_id' and type = 'guru'")->fetchColumn();
        ?>
        <tr>
            <td><?=$row['nama_lengkap']?> </td>
            <td><?=$row['sub_kelas']?></td>
            <?php if($num == 0){ ?>
            <td><span style="color: red;">belum</span></td>
            <?php }elseif($num > 0 && $num < 9){ ?>
            <td><span style="color: orange;">belum selesai</span></td>
            <?php }elseif($num == 9){ ?>
            <td><span style="color: green;">selesai</span></td>
            <?php } ?>
            <?php if($num2 == 0){ ?>
            <td><a href="screening-guru.php?id=<?=$user_id?>">belum</a></td>
            <?php }elseif($num2 == 1){ ?>
            <td><a href="view-screening-guru.php?id=<?=$user_id?>&kelas=<?=$id_kelas?>">selesai</a></td>
            <?php } ?>
        </tr>
<?php } ?>
      </tbody>
    </table>
  </div>
</div>
                </div>
                </div>

                </div>
        </section>
        </main>
<?=footersiswa()?>
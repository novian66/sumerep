<?php 
session_start();
include('functions.php');
$pdo = pdo_connect_mysql();
if(!isset($_SESSION['puskesmas_id'])){
    header('location:index.php');
 }

 $ids = $_SESSION['puskesmas_id'];
 $id_kelas = $_GET['kelas'];
 $id_sekolah = $_GET['sekolah'];
$stmt = $pdo->prepare("SELECT user_puskesmas.*,puskesmas.nama AS nama_puskesmas 
FROM user_puskesmas
JOIN puskesmas ON user_puskesmas.id_puskesmas = puskesmas.id where user_puskesmas.user_id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nama = $row['nama_lengkap'];
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
$stmt3 = $pdo->prepare("select nama from sekolah where id = '$id_sekolah'");
$stmt3->execute();
$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
$nama_sekolah = $row3['nama'];

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
<?=headersiswa('List Siswa',"$nama",$foto,'puskesmas')?>
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
                            <h2 class="page--title h5">List Siswa <?=$kelas?> <?=$nama_sekolah?></h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-puskesmas.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="data-sekolah-puskesmas.php">List Sekolah</a></li>
                                <li class="breadcrumb-item "><a href="list-kelas-puskesmas.php?sekolah=<?=$id_sekolah?>">List kelas <?=$nama_sekolah?></a></li>
                                <li class="breadcrumb-item active"><span>List Siswa <?=$kelas?> <?=$nama_sekolah?></span></li>
                            </ul>
                        </div>

                <div class="col-xl-12">
                <div class="profile--panel">
                    
                <div class="card mb-2">
  <div class="card-body text-left table-responsive">
    <table class="table table-borderless" id="table">
      <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">tes</th>
          <th scope="col">Nama</th>
          <th scope="col">Kelas</th>
          <th scope="col">Screening Siswa</th>
          <th scope="col">Screening Guru</th>
          <th scope="col">Screening Puskesmas</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach($rows2 as $row){ 
        $user_id = $row['id'];
        $num = $pdo->query(" SELECT COUNT(*) FROM check_done WHERE user_id = '$user_id' and type = 'mandiri'")->fetchColumn();
        $num2 = $pdo->query(" SELECT COUNT(*) FROM check_done WHERE user_id = '$user_id' and type = 'guru'")->fetchColumn();
        $num3 = $pdo->query(" SELECT COUNT(*) FROM check_done WHERE user_id = '$user_id' and type = 'puskesmas'")->fetchColumn();
        ?>
        <tr>
            <td><?=$user_id?> </td>
            <td><?=$num2?> </td>
            <td><?=$row['nama_lengkap']?> </td>
            <td><?=$row['sub_kelas']?></td>
            <?php if($num == 0){ ?>
            <td>belum</td>
            <?php }elseif($num > 0 && $num < 9){ ?>
            <td>belum selesai</td>
            <?php }elseif($num == 9){ ?>
            <td>selesai</td>
            <?php } if($num2 == 0){ ?>
            <td>belum</td>
            <?php }elseif($num2 > 0){ ?>
            <td>selesai</td>
            <?php } if($num == 0 or ($num > 0 && $num < 9) or $num2 == 0){ ?>
            <td>screening belum dapat dilakukan</td>
            <?php }elseif($num3 == 0){ ?>
            <td><a href="screening-puskesmas.php?id=<?=$user_id?>&sekolah=<?=$id_sekolah?>&kelas=<?=$id_kelas?>">belum</a></td>
            <?php }elseif($num3 == 1){ ?>
                <td><a href="view-screening-puskesmas.php?id=<?=$user_id?>&sekolah=<?=$id_sekolah?>&kelas=<?=$id_kelas?>" style="color: green;">selesai</a></td>
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
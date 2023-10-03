<?php 
session_start();
include('functions.php');
$pdo = pdo_connect_mysql();
if(!isset($_SESSION['puskesmas_id'])){
    header('location:index.php');
 }

 $ids = $_SESSION['puskesmas_id'];

$stmt = $pdo->prepare("SELECT user_puskesmas.*,puskesmas.nama AS nama_puskesmas 
FROM user_puskesmas
JOIN puskesmas ON user_puskesmas.id_puskesmas = puskesmas.id where user_puskesmas.user_id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nama = $row['nama_lengkap'];
$id_sekolah = $row['id_sekolah'];
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
$stmt2 = $pdo->prepare("SELECT * FROM sekolah WHERE id IN ($id_sekolah);");
$stmt2->execute();
// Fetch the records so we can display them in our template.
$row2s = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<?=headersiswa('List Siswa',"$nama",$foto,'puskesmas')?>
<!-- Sidebar Navigation Start -->
<div class="sidebar--nav">
                <ul>
                    <li>
                    <ul>
                            <li >
                                <a href="index-puskesmas.php">
                                    <i class="fa fa-home"></i>
                                    <span>Home</span>
                                </a>
                            </li>
                            <li >
                                <a href="dashboard-puskesmas.php">
                                    <i class="fa fa-file-archive"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="active">
                                <a href="data-sekolah-puskesmas.php">
                                    <i class="fa fa-user-plus"></i>
                                    <span>Data Sekolah</span>
                                </a>
                            </li>
                            <li>
                                <a href="screening-puskesmass.php">
                                    <i class="fa fa-pencil-alt"></i>
                                    <span>Screening</span>
                                </a>
                            </li>
                            <li>
                                <a href="profile-puskesmas.php">
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
                            <h2 class="page--title h5">List Sekolah</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-guru.php">Home</a></li>
                                <li class="breadcrumb-item active"><span>List Sekolah</span></li>
                            </ul>
                        </div>

                <div class="col-xl-12">
                <div class="profile--panel">
                    
                <div class="card mb-2">
  <div class="card-body text-left table-responsive">
    <table class="table table-borderless" id="table">
      <thead>
        <tr>
          <th scope="col">Nama Sekolah</th>
          <th scope="col">Kelas</th>
          <th scope="col">Jumlah Siswa</th>
          <th scope="col">Check</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach($row2s as $row){ 
        $id_kelas = $row['id_kelas'];
        $stmt3 = $pdo->prepare("SELECT * FROM kelas WHERE id IN ($id_kelas);");
        $stmt3->execute();
        // Fetch the records so we can display them in our template.
        $row3s = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        $kelases = array();
        foreach($row3s as $row3){
            $kelases[] = $row3['kelas'];
        }
        $kelass = implode(',' , $kelases);
        $ides = $row['id'];
        $nums = $pdo->query(" SELECT COUNT(*) FROM user_students WHERE id_sekolah = '$ides' ")->fetchColumn();
        ?>
        <tr>
            <td><?=$row['nama']?> </td>
            <td><?=$kelass?></td>
            <td><?=$nums?></td>
            <td><a href="list-kelas-puskesmas.php?sekolah=<?=$ides?>">check</a></td>
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
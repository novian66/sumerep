<?php 
session_start();
include('functions.php');
$pdo = pdo_connect_mysql();
if(!isset($_SESSION['guru_id'])){
    header('location:index.php');
 }

 $ids = $_SESSION['guru_id'];

$stmt = $pdo->prepare("SELECT user_guru.*,sekolah.nama AS nama_sekolah , sekolah.id_kelas as id_kelas
FROM user_guru
JOIN sekolah ON user_guru.id_sekolah = sekolah.id where user_guru.user_id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nama = $row['nama_lengkap'];
$id_sekolah = $row['id_sekolah'];
$id_kelas = $row['id_kelas'];

$stmts = $pdo->prepare("SELECT * FROM kelas WHERE id IN ($id_kelas);");
$stmts->execute();
// Fetch the records so we can display them in our template.
$rowss = $stmts->fetchAll(PDO::FETCH_ASSOC);
$kelass = array();
foreach($rowss as $row){
    $kelass[] = $row['kelas'];
}

$kelases = implode(', ',$kelass);

$stmt2 = $pdo->prepare("SELECT * FROM user_students WHERE id_sekolah = '$id_sekolah' limit 100");
$stmt2->execute();
// Fetch the records so we can display them in our template.
$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['kelas_id'])){
    $kelas_id = $_GET['kelas_id'];
    $stmts2 = $pdo->prepare("SELECT * FROM kelas WHERE id = '$kelas_id';");
$stmts2->execute();
// Fetch the records so we can display them in our template.
$rowsss = $stmts2->fetch(PDO::FETCH_ASSOC);
$kelas = $rowsss['kelas'];
    $stmt2 = $pdo->prepare("SELECT * FROM user_students WHERE id_sekolah = '$id_sekolah' and id_kelas = '$kelas_id'");
$stmt2->execute();
// Fetch the records so we can display them in our template.
$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
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
                            <?php if(isset($_GET['kelas_id'])){ ?>
                                <h2 class="page--title h5">List Siswa <?=$kelas?></h2>
                                <?php }else{ ?>
                            <h2 class="page--title h5">List Siswa <?=$kelases?></h2>
                            <?php } ?>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-guru.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="data-siswa-guru.php">Data Siswa</a></li>
                                <?php if(isset($_GET['kelas_id'])){ ?>
                                    <li class="breadcrumb-item active"><span>List Siswa <?=$kelas?></span></li>
                                <?php }else{ ?>
                                <li class="breadcrumb-item active"><span>List Siswa <?=$kelases?></span></li>
                                <?php } ?>
                            </ul>
                        </div>

                <div class="col-xl-12">
                <div class="profile--panel">
                <form action="" method="get">
<div class="panel-content pt-5">
    <div class="form-inline">
        <span class="label-text col-md-2 col-form-label text-md-right">Pilih</span>

        <label class="mr-3  mb-3">
            <select name="kelas_id" class="form-control">
            <?php foreach($rowss as $row){ ?>
                <option value="<?=$row['id']?>"><?=$row['kelas']?></option>
            <?php } ?>
            </select>
        </label>

        <input type="submit" name="submit" value="Submit" class="btn btn-sm btn-rounded btn-success mr-2  mb-3">
    </div>
</div>
    </form>
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
            <td><a href="view-screening-guru.php?id=<?=$user_id?>&kelas=<?=$id_kelas?>" style="color: green;">selesai</a></td>
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
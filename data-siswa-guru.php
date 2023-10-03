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
$id_kelas = $row['id_kelas'];
$id_sekolah = $row['id_sekolah'];
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
$stmt2 = $pdo->prepare("SELECT * FROM kelas WHERE id IN ($id_kelas);");
$stmt2->execute();
// Fetch the records so we can display them in our template.
$row2s = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if(!empty($_POST)){

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $pass = md5($_POST['username']);
    $kelas = isset($_POST['kelas']) ? $_POST['kelas'] : '';
    $subkelas = isset($_POST['subkelas']) ? $_POST['subkelas'] : '';
    $namalengkap = isset($_POST['namalengkap']) ? $_POST['namalengkap'] : '';
    $tempatlahir = isset($_POST['tempatlahir']) ? $_POST['tempatlahir'] : '';
    $tanggallahir = isset($_POST['tanggallahir']) ? $_POST['tanggallahir'] : '';
    $jeniskelamin = isset($_POST['jeniskelamin']) ? $_POST['jeniskelamin'] : '';
    $anakke = isset($_POST['anakke']) ? $_POST['anakke'] : '';
    $dari = isset($_POST['dari']) ? $_POST['dari'] : '';
    $tinggalbersama = isset($_POST['tinggalbersama']) ? $_POST['tinggalbersama'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $telpon = isset($_POST['telpon']) ? $_POST['telpon'] : '';
    $disabilitas = isset($_POST['disabilitas']) ? $_POST['disabilitas'] : '';
    $anakkedari = $anakke." dari ".$dari ;

    $stmtss = $pdo->prepare("SELECT max(id) + 1 as maxid FROM `users`");
    $stmtss->execute();
// Fetch the records so we can display them in our template.
    $row3 = $stmtss->fetch(PDO::FETCH_ASSOC);
    $maxid = $row3['maxid'];
    $stmtss2 = $pdo->prepare("INSERT INTO `users` (`id`, `username`, `password`, `name`, `role_id`, `created`) VALUES ('$maxid', '$username', '$pass', '$username', '1', current_timestamp());");
    $stmtss3 = $pdo->prepare("INSERT INTO `user_students` (`user_id`, `id_sekolah`, `id_kelas`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `anak_ke_dari`, `tinggal_bersama`, `alamat`, `telp`, `disabilitas`, `sub_kelas`, `created`) VALUES ( '$maxid', '$id_sekolah', '$kelas', '$namalengkap', '$tempatlahir', '$tanggallahir', '$jeniskelamin', '$anakkedari', '$tinggalbersama', '$alamat', '$telpon', '$disabilitas', '$subkelas', current_timestamp());");
    if($stmtss2->execute()){
        if($stmtss3->execute()){
            echo '<script>
            window.onload = function() {
              document.getElementById("btn-success").click();
            };
          </script>';
        }else{
            echo '<script>
            window.onload = function() {
              document.getElementById("btn-failed").click();
            };
          </script>';
        }
    }else{
        echo '<script>
      window.onload = function() {
        document.getElementById("btn-failed").click();
      };
    </script>';
    }
}
?>
<?=headersiswa('home',"$nama",$foto,'guru')?>
<!-- Sidebar Navigation Start -->
<div class="sidebar--nav">
                <ul>
                    <li>
                        <ul>
                            <li >
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
                            <li class="active">
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

                <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">Data Siswa</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-guru.php">Home</a></li>
                                <li class="breadcrumb-item active"><span>Data Siswa</span></li>
                            </ul>
                        </div>

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
                            <?php foreach($row2s as $row){ 
                               $ids = $row['id'];
                               $nums = $pdo->query(" SELECT COUNT(*) FROM user_students WHERE id_sekolah = '$id_sekolah' and id_kelas = '$ids' ")->fetchColumn();
                                ?>
                            <div class="col">
                                <a href="list-siswa-guru.php?kelas=<?=$ids?>" class="btn btn-rounded btn-default"><?=$row['kelas']?><br> <?=$nums?> siswa</a>
                            </div>
                            <?php } ?>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>

                    <div class="col-xl-12">
                        <!-- Panel Start -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tambah Siswa</h3>
                            </div>

                            <div class="panel-content py-5">
                                <a href="#formInModal" class="btn btn-rounded btn-block btn-success" data-toggle="modal">Tambah Siswa Baru</a>
                                <button id="btn-success" href="#success" class="btn btn-outline-secondary" data-toggle="modal" style="display: none;">View Demo</button>
                                <button id="btn-failed" href="#failed" class="btn btn-outline-secondary" data-toggle="modal" style="display: none;">View Demo</button>
                            </div>
                        </div>
                        <!-- Panel End -->

                        <!-- Modal Start -->
                        <div id="formInModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Siswa</h5>

                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                <form action="" method="post">
                                    <div class="modal-body pt-4">
                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Username</span>
                                                <input type="text" name="username" placeholder="Username ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Kelas</span>
                                                <select class="form-control"
                                                        name="kelas"
                                                        required>
                                                    <?php foreach($row2s as $row){ ?>
                                                        <option value="<?=$row['id']?>"><?=$row['kelas']?></option>
                                                    <?php } ?>
                                                </select>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Sub Kelas</span>
                                                <input type="text" name="subkelas" placeholder="Sub Kelas..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Nama Lengkap</span>
                                                <input type="text" name="namalengkap" placeholder="Nama Lengkap ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Tempat Lahir</span>
                                                <input type="text" name="tempatlahir" placeholder="Tempat Lahir ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Tanggal Lahir</span>
                                                <input type="date" name="tanggallahir" placeholder="Tanggal Lahir ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Jenis Kelamin</span>
                                                <select class="form-control"
                                                        name="jeniskelamin"
                                                        required>
                                                        <option value="6">Laki laki</option>
                                                        <option value="5">Perempuan</option>
                                                </select>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">anak ke </span>
                                                <input type="text" name="anakke" placeholder="Anak ke ..." class="form-control">
                                            </label>
                                            <label>
                                                <span class="label-text">dari </span>
                                                <input type="text" name="dari" placeholder="Dari ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Tinggal Bersama</span>
                                                <input type="text" name="tinggalbersama" placeholder="Tinggal bersama ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Alamat</span>
                                                <input type="text" name="alamat" placeholder="Alamat ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Telpon</span>
                                                <input type="number" name="telpon" placeholder="Nomor Telpon ..." class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <span class="label-text">Disabilitas</span>
                                                <select class="form-control"
                                                        name="disabilitas"
                                                        required>
                                                        <option value="tidak">tidak</option>
                                                        <option value="ya">ya</option>
                                                </select>
                                            </label>
                                        </div>

                                        <input type="submit" name="submit" value="Submit" class="btn btn-sm btn-rounded btn-success">
                                        <button type="button" class="btn btn-sm btn-rounded btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal End -->
                    </div>

<div id="success" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>

                    <button type="button" class="close" onclick="redirectToPage()">&times;</button>
                </div>

                <div align="center" class="modal-body">
                    <img src="assets/img/sweet-alert/custom-icon.png" alt="">
                    <p>Data Ditambahkan !</p>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-success" onclick="redirectToPage()">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <div id="failed" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Failed</h5>

                    <button type="button" class="close" onclick="redirectToPage()">&times;</button>
                </div>

                <div align="center" class="modal-body">
                    <img src="assets/img/sweet-alert/multiply.png" alt="">
                    <p>Data Gagal Ditambahkan !</p>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-success" onclick="redirectToPage()">Ok</button>
                </div>
            </div>
        </div>
    </div>

                </div>
        </section>
        </main>

        <script>
function redirectToPage() {
  // Ganti 'halaman-tujuan.php' dengan URL halaman yang ingin Anda arahkan
  window.location.href = 'data-siswa-guru.php';
}
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<?=footersiswa()?>
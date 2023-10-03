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
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
if(!empty($_POST)){

    $namapanggilan = isset($_POST['namapanggilan']) ? $_POST['namapanggilan'] : '';
    $goldar = isset($_POST['goldar']) ? $_POST['goldar'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $stmts = $pdo->prepare("UPDATE user_students SET nama_panggilan = '$namapanggilan', goldar = '$goldar' , email = '$email' WHERE user_id = '$ids'");
    if($stmts->execute()){
        $msg = 'Success!!';
        echo "<script type='text/javascript'>alert('$msg'); window.location.href='biodata-siswa.php'</script>";
        }else{
            $msg = 'Error!!';
        echo "<script type='text/javascript'>alert('$msg'); window.location.href='biodata-siswa.php'</script>";
    }
}
?>
<?=headersiswa('home',"$nama",$foto,'siswa')?>
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
                            <li class="active">
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
           
                <div class="col-md-12">
                        <!-- Panel Start -->
                        <form action="" method="post">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title" style="color:#fff ;">BIODATA</h3>
                            </div>

                            <div class="panel-content">
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Nama Lengkap</span>
                                        <input type="text" name="nama" class="form-control" value="<?=$nama?>" readonly>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Nama Panggilan</span>
                                        <input type="text" name="namapanggilan" placeholder="Silahkan edit bila perlu" value="<?=$row['nama_panggilan']?>" class="form-control" required>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Tempat / Tanggal Lahir</span>
                                        <input type="text" placeholder="Silahkan edit bila perlu" class="form-control" value="<?=$row['tempat_lahir']?> / <?=$row['tanggal_lahir']?>" readonly>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Golongan Darah</span>
                                        <input type="text" name="goldar" placeholder="Silahkan edit bila perlu" class="form-control" value="<?=$row['goldar']?>" required>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Anak ke - dari</span>
                                        <input type="text" class="form-control" value="<?=$row['anak_ke_dari']?>" readonly>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Tinggal Bersama</span>
                                        <input type="text" class="form-control" value="<?=$row['tinggal_bersama']?>" readonly>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">ALamat</span>
                                        <textarea type="text" class="form-control" readonly><?=$row['alamat']?></textarea>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Telpon / HP</span>
                                        <input type="text" class="form-control" value="<?=$row['telp']?>" readonly>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Email</span>
                                        <input type="email" name="email" placeholder="Silahkan edit bila perlu" class="form-control" value="<?=$row['email']?>" required>
                                    </label>
                                </div>

                                <input type="submit" value="Edit" name="submit" class="btn btn-sm btn-rounded btn-success">
                                <a href="index-siswa.php" class="btn btn-sm btn-rounded btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                        </form>
                        <!-- Panel End -->
                    </div>
                    
                </div>
        </section>
        </main>
<?=footersiswa()?>
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

$stmt = $pdo->prepare("SELECT * from users where id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row2 = $stmt->fetch(PDO::FETCH_ASSOC);

$nama = $row['nama_lengkap'];
$images = $row['foto'];
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
if(!empty($_POST)){

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = md5($_POST['password']);

    $image = $_FILES['foto']['name'];
    $target = "assets/img/avatars/".basename($image);

    $stmts = $pdo->prepare("UPDATE user_guru SET foto = '$image' WHERE user_id = '$ids'");
    $stmts2 = $pdo->prepare("UPDATE users SET username = '$username', password = '$password' WHERE id = '$ids'");

    if(!empty($image)){
        if($stmts->execute()){
        move_uploaded_file($_FILES['foto']['tmp_name'], $target);
        $msg = 'Success!!';
        echo "<script type='text/javascript'>alert('$msg'); window.location.href='profile-guru.php'</script>";
        }else{
            $msg = 'Error!!';
        echo "<script type='text/javascript'>alert('$msg'); window.location.href='profile-guru.php'</script>";
        }
    }else{
        if($stmts2->execute()){
            $msg = 'Success!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='profile-guru.php'</script>";
            }else{
                $msg = 'Error!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='profile-guru.php'</script>";
            }
    }
}
?>
<?=headersiswa('Profile Guru',"$nama",$foto,'guru')?>
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
                            <li class="active">
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
           
                <div class="col-md-12">
                        <!-- Panel Start -->
                        
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title" style="color:#fff ;">Akun</h3>
                            </div>
                            <div class="panel-content">
                            <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                    <label>
                                        <span class="label-text">Sekolah</span>
                                        <input type="text" class="form-control" value="<?=$row['nama_sekolah']?>" readonly>
                                    </label>
                                </div>
                            <div class="form-group">
                                    <label>
                                        <span class="label-text">foto</span>
                                        <?php if(empty($images)) :?>
                                            <a href="assets/img/avatars/blank.png"><img src="assets/img/avatars/blank.png" class="img-fluid rounded-circle" style="width:120px;height:120px;"></a>
                                        <?php else :?>
                                            <a href="assets/img/avatars/<?=$images?>"><img src="assets/img/avatars/<?=$images?>" class="img-fluid rounded-circle" style="width:120px;height:120px;"></a>
                                        <?php endif;?>
                                        <input type="file" accept="image/*" id="files" name="foto" class="form-control">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Username</span>
                                        <input type="text" name="username" class="form-control" value="<?=$row2['username']?>" required>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Password</span>
                                        <input type="password" name="password" placeholder="Silahkan edit bila perlu" class="form-control" required>
                                    </label>
                                </div>

                                

                                <input type="submit" value="Edit" name="submit" class="btn btn-sm btn-rounded btn-success">
                                <button type="button" class="btn btn-sm btn-rounded btn-outline-secondary">Cancel</button>
                            </div>
                        </div>
                        </form>
                        <!-- Panel End -->
                    </div>
                    
                </div>
        </section>
        </main>
<?=footersiswa()?>
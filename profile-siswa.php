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

$stmt = $pdo->prepare("SELECT * from users where id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row2 = $stmt->fetch(PDO::FETCH_ASSOC);

$nama = $row['nama_lengkap'];
$images = $row['foto'];
if(!empty($_POST)){

    $username = isset($_POST['namapanggilan']) ? $_POST['namapanggilan'] : '';
    $password = md5($_POST['password']);

    $image = $_FILES['foto']['name'];
    $target = "assets/img/avatars/".basename($image);

    $stmts = $pdo->prepare("UPDATE user_students SET foto = '$image' WHERE user_id = '$ids'");
    $stmts2 = $pdo->prepare("UPDATE users SET username = '$username', password = '$password' WHERE id = '$ids'");

    if(!empty($image)){
        if($stmts->execute()){
        move_uploaded_file($_FILES['foto']['tmp_name'], $target);
        $msg = 'Success!!';
        echo "<script type='text/javascript'>alert('$msg'); window.location.href='biodata-siswa.php'</script>";
        }else{
            $msg = 'Error!!';
        echo "<script type='text/javascript'>alert('$msg'); window.location.href='biodata-siswa.php'</script>";
        }
    }else{
        if($stmts2->execute()){
            $msg = 'Success!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='biodata-siswa.php'</script>";
            }else{
                $msg = 'Error!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='biodata-siswa.php'</script>";
            }
    }
}
?>
<?=headersiswa('home',"$nama",'blank.png')?>
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
                            <li class="active">
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
                        <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title" style="color:#fff ;">Akun</h3>
                            </div>

                            <div class="panel-content">
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
                                        <input type="text" name="nama" class="form-control" value="<?=$row2['username']?>" readonly>
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
        <script type="text/javascript">
      const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
      const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))


      function hideImages(){

        var remove = document.getElementById('preview');
        remove.removeChild(remove.childNodes[0]);
        document.getElementById('files').val('');

      }

      


      const preview = (file) => {
        const fr = new FileReader();
        fr.onload = () => {
          const img = document.createElement("img");
          img.src = fr.result;  // String Base64 
          img.alt = file.name;
          document.querySelector('#preview').append(img);
        };
        fr.readAsDataURL(file);
      };

      document.querySelector("#files").addEventListener("change", (ev) => {
        if (!ev.target.files) return; // Do nothing.
        [...ev.target.files].forEach(preview)
            document.getElementById('preview').style.visibility = "visible";
            document.getElementById('hapusImg').style.visibility = "visible";
        ;
      });
    </script>
<?=footersiswa()?>
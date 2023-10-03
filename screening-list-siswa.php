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
$id_siswa = $row['id'];
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
$stmts = $pdo->prepare("SELECT * FROM `question_categories` WHERE role_id = '1'");
$stmts->execute();
// Fetch the records so we can display them in our template.
$rows = $stmts->fetchAll(PDO::FETCH_ASSOC);


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
                            <li>
                                <a href="biodata-siswa.php">
                                    <i class="fa fa-users"></i>
                                    <span>Biodata</span>
                                </a>
                            </li>
                            <li class="active">
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
<div class="col-xl-3">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Screening List</h3>
                            </div>

                            <div class="todo--panel">
                                    <ul class="list-group" data-trigger="scrollbar">
                                    <?php  foreach($rows as $row){ 
                                        $categoriesid = $row['id'];
                                        $check = $pdo->query(" SELECT COUNT(*) FROM check_done WHERE categories_id = '$categoriesid' and user_id = '$id_siswa' and done = 'yes' ")->fetchColumn();

                                        if($check > 0){
                                        ?>

                                        <li class="list-group-item">
                                        <a href=""><label class="todo--label">
                                                <input type="checkbox" name="todo_id" value="<?=$row['id']?>" class="todo--input" disabled checked>
                                                <span class="todo--text"><?=$row['name']?></span>
                                            </label></a>
                                        </li>
                                        <?php }else { ?>
                                            <li class="list-group-item">
                                        <label class="todo--label">
                                        <a href="screening-siswa.php?id=<?=$row['id']?>"><input type="checkbox" name="todo_id" value="<?=$row['id']?>" class="todo--input" disabled >
                                                <span class="todo--text"><?=$row['name']?></span></a>
                                            </label></a>
                                        </li>
                                        <?php }}?>
                                    </ul>
                            </div>
                        </div>
                    </div>
                </section>
            

        </main>
<?=footersiswa()?>
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
$nama = $row['nama_lengkap'];
$foto = $row['foto'];
if(empty($foto)):
    $foto = "blank.png";
endif;
$stmts2 = $pdo->prepare("SELECT * from master_datas where name = 'tahun_ajar_aktif'");
$stmts2->execute();
// Fetch the records so we can display them in our template.
$row2s = $stmts2->fetch(PDO::FETCH_ASSOC);
$tahunajar = $row2s['id'];

$id_siswa = $_GET['id'];

$stmts3 = $pdo->prepare("SELECT * FROM `question_categories` WHERE role_id = '2'");
$stmts3->execute();
// Fetch the records so we can display them in our template.
$rows3 = $stmts3->fetchAll(PDO::FETCH_ASSOC);
$idcategories = array();



if(isset($_POST['submit'])){

    foreach($rows3 as $row){
        $id_question = $row['id'];
        $stmtsss = $pdo->prepare("SELECT * FROM `master_question` WHERE categories_id = '$id_question'");
        $stmtsss->execute();
                    // Fetch the records so we can display them in our template.
        $rowsss = $stmtsss->fetchAll(PDO::FETCH_ASSOC);
        foreach($rowsss as $row2){
        $i = $row2['id'];
        $check1 = $row2['question_check_option'];
        $check2 = $row2['question_need_answer2'];
        if(is_null($check1) and is_null($check2)){
            $input[$i] = $_POST['input'.$i];
            $number[$i] = 44;
        }elseif(!is_null($check1) and is_null($check2)){
            $input[$i] = '';
            $number[$i] = $_POST['checkbox'.$i];
        }elseif($row2['question_need_answer2'] == '2' and !is_null($check1)){
            $input[$i] = $_POST['ya_input'.$i];
            $number[$i] = $_POST['checkbox'.$i];
        }elseif($row2['question_need_answer2'] == '16,17,18,19,20' and $row2['question_check_option'] == '16,17,18,19,20'){
            $input[$i] = $_POST['input'.$i];
            $number[$i] = $_POST['checkbox'.$i];
        }elseif($row2['question_need_answer2'] == '21,22' and $row2['question_check_option'] == '21,22'){
            $input[$i] = '';
            $number[$i] = $_POST['checkbox'.$i];
        }
        $stmt3 = $pdo->prepare("INSERT INTO `answer_question` (`question_id`, `answer`, `answer2`, `tahun_ajar_id`, `created`, `created_by`) VALUES ( '$i', '$number[$i]', '$input[$i]', '$tahunajar', current_timestamp(), '$id_siswa')");
        $stmt3->execute();
        }
        $idcategories[] = $id_question;
    }
    $idcats = implode(',', $idcategories);
    $stmt4 = $pdo->prepare("INSERT INTO `check_done` (`categories_id`, `user_id`, `done`, `created` , `type`) VALUES ('$idcats', '$id_siswa', 'yes', current_timestamp() , 'guru');");
        if($stmt4->execute()){
            $msg = 'Success!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='data-siswa-guru.php'</script>";
            }else{
                $msg = 'Error!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='data-siswa-guru.php'</script>";
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

            <section class="main--content">


<form action="" method="post">
                <?php foreach($rows3 as $row) {
                    $id_question = $row['id'];
                    $names = $row['name'];
                    $stmtss = $pdo->prepare("SELECT * FROM `master_question` WHERE categories_id = '$id_question'");
                    $stmtss->execute();
                    // Fetch the records so we can display them in our template.
                    $rowss = $stmtss->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="panel-heading">
                        <h5 style="color:#009378;"><?=$names?></h5><br>           
                    </div>
                    <div class="panel-content">
                        <?php foreach($rowss as $row2){
                        $nomors = $row2['id'];
                        $check1 = $row2['question_check_option'];
                        $check2 = $row2['question_need_answer2'];
                        if(is_null($check1)){
                            ?>
                                <div class="form-group row">
                                    <span class="label-text col-md-2 col-form-label text-md-right"><?=$row2['name']?></span>

                                    <div class="col-md-10">
                                        <input type="text" name="input<?=$nomors?>" class="form-control">
                                    </div>
                                </div>
                        <?php } if($check1 == '1,2' and is_null($check2)) { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                            </div>
                            </label>
                        </div>
                        <?php } elseif($check1 == '1,2' and $check2 == '2'){ ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" class="form-check-input" onchange="toggleInput('ya<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Ya</span>
                                    <input class="form-control"
                                       type="text"
                                       id="ya<?=$nomors?>_input"
                                       name="ya_input<?=$nomors?>"
                                       placeholder="Sebutkan ..."
                                       style="display: none">
                                </label>
                                

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" class="form-check-input" onchange="toggleInput2('tidak<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                            </div>
                            </label>
                        </div>
                        <?php } if($check1 == '16,17,18,19,20') { ?>
                            <div class="form-group row">
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10">
                                        <input type="text" name="input<?=$nomors?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="16" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Sangat Kurus </span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="19" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kurus</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="17" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Normal</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="20" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Gemuk</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="18" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Sangat Gemuk</span>
                                </label>
                            </div>
                        </div>
                        
                        <?php } if($check1 == '21,22') { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="21" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Tidak Sehat</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="22" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Sehat</span>
                                </label>
                            </div>
                            </label>
                        </div>
                        <?php } if($check1 == '31,32,33,36,34') { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="31" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Baik Sekali</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="36" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Baik</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="32" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Cukup</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="34" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kurang</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="33" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kurang Sekali</span>
                                </label>
                            </div>
                            </label>
                        </div>
                        <?php } } ?>
                        </div>
                        <?php } ?>
                        <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
            
            </form>
            
    </section>
    <script>
    function toggleInput(checkboxId, inputId) {
        var checkbox = document.getElementById(checkboxId);
        var input = document.getElementById(inputId);

        if (checkbox.checked) {
            input.style.display = "block";
        } else if(!checkbox.checked){
            input.style.display = "none";
        }
    }

    function toggleInput2(checkboxId, inputId) {
        var checkbox = document.getElementById(checkboxId);
        var input = document.getElementById(inputId);

        if (checkbox.checked) {
            input.style.display = "none";
        } else if(!checkbox.checked){
            input.style.display = "block";
        }
    }
</script>

                </div>
        </section>
        </main>
<?=footersiswa()?>
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

$stmts2 = $pdo->prepare("SELECT * from master_datas where name = 'tahun_ajar_aktif'");
$stmts2->execute();
// Fetch the records so we can display them in our template.
$row2s = $stmts2->fetch(PDO::FETCH_ASSOC);
$tahunajar = $row2s['id'];

$id_question = $_GET['id'];

$stmts = $pdo->prepare("SELECT * FROM `master_question` WHERE categories_id = '$id_question' ");
$stmts->execute();
// Fetch the records so we can display them in our template.
$rows = $stmts->fetchAll(PDO::FETCH_ASSOC);
$stmt2 = $pdo->prepare("SELECT * from question_categories where id = '$id_question';");
$stmt2->execute();
// Fetch the records so we can display them in our template.
$row1 = $stmt2->fetch(PDO::FETCH_ASSOC);
$names = $row1['name'];

if(isset($_GET['id']) == '1' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '2' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '3' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '4' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '5' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '6' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '18' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '19' && isset($_GET['insert']) == 'true' || isset($_GET['id']) == '20' && isset($_GET['insert']) == 'true'){
    foreach($rows as $row){
        $i = $row['id'];
        $number[$i] = $_POST['checkbox'.$i];
        if($row['question_need_answer2'] == '2'){
            $input[$i] = $_POST['ya_input'.$i];
        }else{
            $input[$i] = '';
        }
        $stmt3 = $pdo->prepare("INSERT INTO `answer_question` (`question_id`, `answer`, `answer2`, `tahun_ajar_id`, `created`, `created_by`) VALUES ( '$i', '$number[$i]', '$input[$i]', '$tahunajar', current_timestamp(), '$id_siswa')");
        $stmt3->execute();
    }
    $stmt4 = $pdo->prepare("INSERT INTO `check_done` (`categories_id`, `user_id`, `done`, `created` , `type`) VALUES ('$id_question', '$id_siswa', 'yes', current_timestamp() , 'mandiri');");
        if($stmt4->execute()){
            $msg = 'Success!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='screening-list-siswa.php'</script>";
            }else{
                $msg = 'Error!!';
            echo "<script type='text/javascript'>alert('$msg'); window.location.href='screening-list-siswa.php'</script>";
            }
}
?>
<?=headersiswa('home',"$nama",'blank.png')?>
<!-- Sidebar Navigation Start -->
<style>
        ul {
  list-style: none;
  display: table;
}

li {
  display: table-row;
}

b {
  display: table-cell;
  padding-right: 1em;
}
</style>
<div class="sidebar--nav">
                <ul>
                    <li>
                        <ul>
                            <li>
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
                            <li  class="active">
                                <a href="dass42-siswa.php">
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
        <section class="page--header">
                <div class="container-fluid">
                    <div class="row">
                <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">Screening <?=$names?></h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-siswa.php">Home</a></li>
                                <li class="breadcrumb-item active"><span>Screening <?=$names?></span></li>
                            </ul>
                        </div>

                    </div>
                </div>
        </section>

        <?php if($id_question == '1' || $id_question == '2'){ ?>
            <section class="main--content">

<div class="panel-heading">
    <h5 style="color:#009378;"><?=$names?></h5><br>           
</div>
<form action="screening-siswa.php?id=<?=$id_question?>&insert=true" method="post">
            <div class="panel-content">
                <?php foreach($rows as $row) {
                    $nomors = $row['id'];
                    $check = $row['question_need_answer2'];
                    if($check == '2'){
                    ?>
                        <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
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
                        <?php }else { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
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
                        <?php } } ?>
                        <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
            </div>
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
        <?php }else if($id_question == '3'){ ?>
            <section class="main--content">

<div class="panel-heading">
    <h5 style="color:#009378;"><?=$names?></h5><br>           
</div>
<form action="screening-siswa.php?id=<?=$id_question?>&insert=true" method="post">
            <div class="panel-content">
                <?php foreach($rows as $row) {
                    $nomors = $row['id']; ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="3" id="tidaktahu<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Tidak Tahu</span>
                                </label>
                            </div>
                            </label>
                        </div>
                        <?php }  ?>
                        <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
            </div>
            </form>
            
    </section>
            <?php } else if($id_question == '4'){ ?>
                
                <section class="main--content">

<div class="panel-heading">
    <h5 style="color:#009378;"><?=$names?></h5><br>           
</div>
<form action="screening-siswa.php?id=<?=$id_question?>&insert=true" method="post">
            <div class="panel-content">
                <?php foreach($rows as $row) {
                    $nomors = $row['id']; 
                    
                    if($row['question_check_option'] == '4,5,6'){ ?>
                
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="4" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Selalu</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="5" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kadang</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="6" id="tidaktahu<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Tidak Pernah</span>
                                </label>
                            </div>
                            </label>
                        </div>
                        <?php } else { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                            </div>
                            </label>
                        </div>
                        <?php }}  ?>
                        <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
            </div>
            </form>
            
    </section>

                <?php }else if($id_question == '5'){ ?>

                    <section class="main--content">

<div class="panel-heading">
    <h5 style="color:#009378;"><?=$names?></h5><br>           
</div>
<form action="screening-siswa.php?id=<?=$id_question?>&insert=true" method="post">
            <div class="panel-content">
                <?php foreach($rows as $row) {
                    $nomors = $row['id']; ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="37" id="ya<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">Benar</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="38" id="tidak<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">Salah</span>
                                </label>

                            </div>
                            </label>
                        </div>
                        <?php }  ?>
                        <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
            </div>
            </form>
            
    </section>
    <?php }else if($id_question == '6'){ ?>

        <section class="main--content">

<div class="panel-heading">
    <h5 style="color:#009378;"><?=$names?></h5><br>           
</div>
<form action="screening-siswa.php?id=<?=$id_question?>&insert=true" method="post">
            <div class="panel-content">
                <?php foreach($rows as $row) {
                    $nomors = $row['id']; ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="7" id="ya<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">Normal</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="8" id="tidak<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">Borderline</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="9" id="tidaks<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">Abnormal</span>
                                </label>

                            </div>
                            </label>
                        </div>
                        <?php }  ?>
                        <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
            </div>
            </form>
            
    </section>

                <?php }elseif($id_question == '18' or $id_question == '19' or $id_question == '20'){ ?>

                    <section class="main--content">

<div class="panel-heading">
    <h5 style="color:#009378;"><?=$names?></h5><br>           
</div>
<div class="panel-heading">
            <h5 style="color:#009378;">Keterangan</h5><br>           
            <li style="color:#009378 ;"><b>1 </b>: Tidak Setuju</li>
            <li style="color:#009378 ;"><b>2 </b>: Kurang Setuju</li>
            <li style="color:#009378 ;"><b>3 </b>: Cukup Setuju</li>
            <li style="color:#009378 ;"><b>4 </b>: Setuju</li>
            <li style="color:#009378 ;"><b>5 </b>: Sangat Setuju</li>
        </div>
<form action="screening-siswa.php?id=<?=$id_question?>&insert=true" method="post">
            <div class="panel-content">
                <?php foreach($rows as $row) {
                    $nomors = $row['id']; ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row['name']?></span>
                                <div class="col-md-10 form-inline">
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="39" id="ya<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">1</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="40" id="tidak<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">2</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="41" id="tidaks<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">3</span>
                                </label>

                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="42" id="tidaks<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">4</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="43" id="tidaks<?=$nomors?>" class="form-check-input" required>
                                    <span class="form-check-label">5</span>
                                </label>

                            </div>
                            </label>
                        </div>
                        <?php }  ?>
                        <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
            </div>
            </form>
            
    </section>

                    <?php } else { ?>


        <section class="main--content">

        <div class="panel-heading">
            <h5 style="color:#009378;">Keterangan</h5><br>           
            <li style="color:#009378 ;"><b>0 </b>: Tidak pernah</li>
            <li style="color:#009378 ;"><b>1-2 </b>: Sesuai yang di alami kadang-kadang</li>
            <li style="color:#009378 ;"><b>3 </b>: Sering mengalami</li>
        </div>
                    <div class="panel-content">
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Menjadi marah karena hal-hal kecil/sepele</span>
                                        <div class="col-md-10 form-inline">
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio1" value="0" class="form-radio-input">
                                            <span class="form-radio-label">0</span>
                                        </label>
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio1" value="1" class="form-radio-input">
                                            <span class="form-radio-label">1</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio1" value="2" class="form-radio-input">
                                            <span class="form-radio-label">2</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio1" value="3" class="form-radio-input">
                                            <span class="form-radio-label">3</span>
                                        </label>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Menjadi marah karena hal-hal kecil/sepele</span>
                                        <div class="col-md-10 form-inline">
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio2" value="0" class="form-radio-input">
                                            <span class="form-radio-label">0</span>
                                        </label>
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio2" value="1" class="form-radio-input">
                                            <span class="form-radio-label">1</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio2" value="2" class="form-radio-input">
                                            <span class="form-radio-label">2</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio2" value="3" class="form-radio-input">
                                            <span class="form-radio-label">3</span>
                                        </label>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Menjadi marah karena hal-hal kecil/sepele</span>
                                        <div class="col-md-10 form-inline">
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio3" value="0" class="form-radio-input">
                                            <span class="form-radio-label">0</span>
                                        </label>
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio3" value="1" class="form-radio-input">
                                            <span class="form-radio-label">1</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio3" value="2" class="form-radio-input">
                                            <span class="form-radio-label">2</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio3" value="3" class="form-radio-input">
                                            <span class="form-radio-label">3</span>
                                        </label>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Menjadi marah karena hal-hal kecil/sepele</span>
                                        <div class="col-md-10 form-inline">
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio4" value="0" class="form-radio-input">
                                            <span class="form-radio-label">0</span>
                                        </label>
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio4" value="1" class="form-radio-input">
                                            <span class="form-radio-label">1</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio4" value="2" class="form-radio-input">
                                            <span class="form-radio-label">2</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio4" value="3" class="form-radio-input">
                                            <span class="form-radio-label">3</span>
                                        </label>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="label-text">Menjadi marah karena hal-hal kecil/sepele</span>
                                        <div class="col-md-10 form-inline">
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio5" value="0" class="form-radio-input">
                                            <span class="form-radio-label">0</span>
                                        </label>
                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio5" value="1" class="form-radio-input">
                                            <span class="form-radio-label">1</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio5" value="2" class="form-radio-input">
                                            <span class="form-radio-label">2</span>
                                        </label>

                                        <label class="form-radio mr-3">
                                            <input type="radio" name="radio5" value="3" class="form-radio-input">
                                            <span class="form-radio-label">3</span>
                                        </label>
                                        </div>
                                    </label>
                                </div>
                                <input type="submit" value="Submit" name="submit" class="btn btn-sm btn-rounded btn-success">
                    </div>
            </section>
            <?php } ?>
            <button type="button" id="btn-success"
                                                    class="btn btn-danger"
                                                    data-sweet-alert="success" style="display:none ;"></button>
        </main>
<?=footersiswa()?>
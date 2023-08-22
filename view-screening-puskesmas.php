<?php 
session_start();
include('functions.php');
$pdo = pdo_connect_mysql();
if(!isset($_SESSION['puskesmas_id'])){
    header('location:index.php');
 }

 $ids = $_SESSION['puskesmas_id'];
 $stmt = $pdo->prepare("SELECT user_puskesmas.*,puskesmas.nama AS nama_puskesmas , puskesmas.alamat AS alamat_puskesmas
 FROM user_puskesmas
 JOIN puskesmas ON user_puskesmas.id_puskesmas = puskesmas.id where user_puskesmas.user_id = '$ids';");
$stmt->execute();
// Fetch the records so we can display them in our template.
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nama = $row['nama_lengkap'];

$stmts2 = $pdo->prepare("SELECT * from master_datas where name = 'tahun_ajar_aktif'");
$stmts2->execute();
// Fetch the records so we can display them in our template.
$row2s = $stmts2->fetch(PDO::FETCH_ASSOC);
$tahunajar = $row2s['id'];

$id_siswa = $_GET['id'];
$id_sekolah = $_GET['sekolah'];
$id_kelas = $_GET['kelas'];

$stmts3 = $pdo->prepare("SELECT * FROM `question_categories` WHERE role_id = 1 or role_id = 2 ORDER BY `question_categories`.`role_id` ASC");
$stmts3->execute();
// Fetch the records so we can display them in our template.
$rows3 = $stmts3->fetchAll(PDO::FETCH_ASSOC);

$stmts3s = $pdo->prepare("SELECT * FROM `question_categories` WHERE role_id = 3");
$stmts3s->execute();
// Fetch the records so we can display them in our template.
$rows3s = $stmts3s->fetchAll(PDO::FETCH_ASSOC);
?>
<?=headersiswa('Lihat hasil Screening',"$nama",'blank.png')?>
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
                    <?php if($id_question == '18' or $id_question == '19' or $id_question == '20'){ ?>
                    <div class="panel-heading">
                        <h5 style="color:#009378;">Keterangan</h5><br>           
                        <li style="color:#009378 ;"><b>1 </b>: Tidak Setuju</li>
                        <li style="color:#009378 ;"><b>2 </b>: Kurang Setuju</li>
                        <li style="color:#009378 ;"><b>3 </b>: Cukup Setuju</li>
                        <li style="color:#009378 ;"><b>4 </b>: Setuju</li>
                        <li style="color:#009378 ;"><b>5 </b>: Sangat Setuju</li>
                    </div>
                    <?php }else{} ?>
                    <div class="panel-content">
                        <?php foreach($rowss as $row2){
                        $nomors = $row2['id'];
                        $stmtss2 = $pdo->prepare("SELECT * FROM `answer_question` WHERE question_id = '$nomors' and created_by = '$id_siswa'");
                        $stmtss2->execute();
                        $rowss2 = $stmtss2->fetch(PDO::FETCH_ASSOC);
                        $answer1 = $rowss2['answer'];
                        $answer2 = $rowss2['answer2'];
                        $check1 = $row2['question_check_option'];
                        $check2 = $row2['question_need_answer2'];
                        if(is_null($check1)){
                            ?>
                                <div class="form-group row">
                                    <span class="label-text col-md-2 col-form-label text-md-right"><?=$row2['name']?></span>

                                    <div class="col-md-10">
                                        <input type="text" name="input<?=$nomors?>" value="<?=$answer2?>" readonly class="form-control">
                                    </div>
                                </div>
                        <?php } if($check1 == '1,2' and is_null($check2)) { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                    <?php if($answer1 == '2') {?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" checked disabled class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" disabled checked class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                        <?php } elseif($check1 == '1,2' and $check2 == '2'){ ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                    <?php if($answer1 == '2') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="2" id="ya<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput('ya<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Ya</span>
                                    <input class="form-control"
                                       type="text"
                                       id="ya<?=$nomors?>_input"
                                       name="ya_input<?=$nomors?>"
                                       placeholder="Sebutkan ..."
                                       value="<?=$answer2?>" readonly>
                                </label>
                                

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput2('tidak<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput('ya<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Ya</span>
                                    <input class="form-control"
                                       type="text"
                                       id="ya<?=$nomors?>_input"
                                       name="ya_input<?=$nomors?>"
                                       placeholder="Sebutkan ..."
                                       style="display: none" readonly>
                                </label>
                                

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="1" id="tidak<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput2('tidak<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                        <?php } if($check1 == '16,17,18,19,20') { ?>
                            <div class="form-group row">
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10">
                                        <input type="text" name="input<?=$nomors?>" value="<?=$answer2?>" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="col-md-10 form-inline">
                                <?php if($answer1 == '16') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="16" checked disabled id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Sangat Kurus </span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="16" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Sangat Kurus </span>
                                </label>
                                <?php }if($answer1 == '19') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="19" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Kurus</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="19" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Kurus</span>
                                </label>
                                <?php }if($answer1 == '17') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="17" checked id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Normal</span>
                                </label>
                                <?php }else{ ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="17" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Normal</span>
                                </label>
                                <?php }if($answer1 == '20') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="20" checked id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Gemuk</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="20" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Gemuk</span>
                                </label>
                                <?php }if($answer1 == '18') { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="18" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Sangat Gemuk</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="18" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Sangat Gemuk</span>
                                </label>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php } if($check1 == '21,22') { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '21') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="21" checked id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak Sehat</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="22" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Sehat</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="21" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak Sehat</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="22" checked id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Sehat</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                        <?php } if($check1 == '31,32,33,36,34') { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '31') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="31" checked disabled id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Baik Sekali</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="31" disabled id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Baik Sekali</span>
                                </label>

                                <?php }if($answer1 == '36') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="36" disabled checked id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Baik</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="36" disabled id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Baik</span>
                                </label>

                                <?php }if($answer1 == '32') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="32" checked disabled id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Cukup</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="32" disabled id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Cukup</span>
                                </label>

                                <?php }if($answer1 == '34') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="34" checked disabled id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kurang</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="34" disabled id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kurang</span>
                                </label>

                                <?php }if($answer1 == '33') { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="33" disabled checked id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kurang Sekali</span>
                                </label>
                                <?php }else { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="33" disabled id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Kurang Sekali</span>
                                </label>
                                <?php }?>
                            </div>
                            </label>
                        </div>
                        <?php } if($check1 == '1,2,3'){ ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '2') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="2" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>
                                <?php }if($answer1 == '1') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="1" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php }if($answer1 == '3') { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="3" id="tidaktahu<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak Tahu</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="3" id="tidaktahu<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak Tahu</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                    <?php } if($check1 == '4,5,6'){ ?>
                        <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '4') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="4" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Selalu</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="4" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Selalu</span>
                                </label>

                                <?php }if($answer1 == '5') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="5" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Kadang</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="5" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Kadang</span>
                                </label>

                                <?php }if($answer1 == '6') { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="6" id="tidaktahu<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak Pernah</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="6" id="tidaktahu<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak Pernah</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                    <?php } if($check1 == '7,8,9'){ ?>
                        <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '7') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="7" id="ya<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Normal</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="7" id="ya<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Normal</span>
                                </label>

                                <?php }if($answer1 == '8') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="8" id="tidak<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Borderline</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="8" id="tidak<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Borderline</span>
                                </label>

                                <?php }if($answer1 == '9') { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="9" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Abnormal</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="9" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Abnormal</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                    <?php } if($check1 == '39,40,41,42,43'){ ?>
                        <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '39') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="39" checked id="ya<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">1</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="39" id="ya<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">1</span>
                                </label>

                                <?php }if($answer1 == '40') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="40" id="tidak<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">2</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="40" id="tidak<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">2</span>
                                </label>

                                <?php }if($answer1 == '41') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="41" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">3</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="41" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">3</span>
                                </label>

                                <?php }if($answer1 == '42') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="42" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">4</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="42" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">4</span>
                                </label>

                                <?php }if($answer1 == '43') { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="43" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">5</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="43" id="tidaks<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">5</span>
                                </label>
                                <?php }?>
                            </div>
                            </label>
                        </div>
                    <?php } if($check1 == '37,38'){ ?>
                        <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '37') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="37" id="ya<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Benar</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="37" id="ya<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Benar</span>
                                </label>

                                <?php }if($answer1 == '38') { ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="38" id="tidak<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Salah</span>
                                </label>
                                <?php }else { ?>
                                    <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="38" id="tidak<?=$nomors?>" disabled class="form-check-input" required>
                                    <span class="form-check-label">Salah</span>
                                </label>
                                <?php }?>

                            </div>
                            </label>
                        </div>
                    <?php } } ?>
                        </div>
                        <?php } ?>

                                              
            <div class="panel-heading">
                <h5 style="color:red;">Screening Puskesmas Dimulai disini </h5><br>           
            </div>
                <?php foreach($rows3s as $row) {
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
                        $stmtss2 = $pdo->prepare("SELECT * FROM `answer_question` WHERE question_id = '$nomors' and created_by = '$id_siswa'");
                        $stmtss2->execute();
                        $rowss2 = $stmtss2->fetch(PDO::FETCH_ASSOC);
                        $answer1 = $rowss2['answer'];
                        $answer2 = $rowss2['answer2'];
                        $check1 = $row2['question_check_option'];
                        $check2 = $row2['question_need_answer2'];
                        if(is_null($check1)){
                            ?>
                                <div class="form-group row">
                                    <span class="label-text col-md-2 col-form-label text-md-right"><?=$row2['name']?></span>

                                    <div class="col-md-10">
                                        <input type="text" name="input<?=$nomors?>" value="<?=$answer2?>" readonly class="form-control">
                                    </div>
                                </div>
                        <?php } if($check1 == '1,2' and is_null($check2)) { ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                    <?php if($answer1 == '2') {?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" checked disabled class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" disabled class="form-check-input">
                                    <span class="form-check-label">Ya</span>
                                </label>

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" disabled checked class="form-check-input">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                        <?php } elseif($check1 == '1,2' and $check2 == '2'){ ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                    <?php if($answer1 == '2') { ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="2" id="ya<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput('ya<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Ya</span>
                                    <input class="form-control"
                                       type="text"
                                       id="ya<?=$nomors?>_input"
                                       name="ya_input<?=$nomors?>"
                                       placeholder="Sebutkan ..."
                                       value="<?=$answer2?>" readonly>
                                </label>
                                

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="1" id="tidak<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput2('tidak<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="2" id="ya<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput('ya<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Ya</span>
                                    <input class="form-control"
                                       type="text"
                                       id="ya<?=$nomors?>_input"
                                       name="ya_input<?=$nomors?>"
                                       placeholder="Sebutkan ..."
                                       style="display: none" readonly>
                                </label>
                                

                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="1" id="tidak<?=$nomors?>" disabled class="form-check-input" onchange="toggleInput2('tidak<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Tidak</span>
                                </label>
                                <?php } ?>
                            </div>
                            </label>
                        </div>
                        <?php } if($check1 == '7,35' and $check2 == '35'){ ?>
                            <div class="form-group">
                            <label>
                                <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '35'){ ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="35" id="ya<?=$nomors?>" class="form-check-input" onchange="toggleInput('ya<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Ada Gangguan</span>
                                    <input class="form-control"
                                       type="text"
                                       id="ya<?=$nomors?>_input"
                                       name="ya_input<?=$nomors?>"
                                       value="<?=$answer2?>"
                                       placeholder="Sebutkan ...">
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="35" id="ya<?=$nomors?>" class="form-check-input" onchange="toggleInput('ya<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Ada Gangguan</span>
                                    <input class="form-control"
                                       type="text"
                                       id="ya<?=$nomors?>_input"
                                       name="ya_input<?=$nomors?>"
                                       placeholder="Sebutkan ..."
                                       style="display: none">
                                </label>

                                <?php }if($answer1 == '7'){ ?>
                                <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="7" id="tidak<?=$nomors?>" class="form-check-input" onchange="toggleInput2('tidak<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Normal</span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="7" id="tidak<?=$nomors?>" class="form-check-input" onchange="toggleInput2('tidak<?= $nomors ?>', 'ya<?= $nomors ?>_input')">
                                    <span class="form-check-label">Normal</span>
                                </label>
                                <?php }?>
                            </div>
                            </label>
                        </div>
                        <?php } if($check1 == '22,28,29') { ?>
                            <div class="form-group">
                            <span class="label-text"><?=$row2['name']?></span>
                                <div class="col-md-10 form-inline">
                                <?php if($answer1 == '22'){ ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="22" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Sehat </span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="22" id="ya<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Sehat </span>
                                </label>

                                <?php }if($answer1 == '28'){ ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="28" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Infeksi</span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="28" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Infeksi</span>
                                </label>

                                <?php }if($answer1 == '29'){  ?>
                                <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" checked value="29" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Serumen</span>
                                </label>
                                <?php }else{ ?>
                                    <label class="form-check mr-3">
                                    <input type="radio" name="checkbox<?=$nomors?>" value="29" id="tidak<?=$nomors?>" class="form-check-input">
                                    <span class="form-check-label">Serumen</span>
                                </label>
                                <?php }?>
                            </div>
                        </div>
                        <?php } } ?>
                        </div>
                        <?php } ?>


    </section>
    <script>
    function toggleInput(checkboxId, inputId) {
        var checkbox = document.getElementById(checkboxId);
        var input = document.getElementById(inputId);

        if (checkbox.checked) {
            input.style.display = "block";
            input.required = true;
        } else {
            input.style.display = "none";
            input.required = false;
        }
    }

    function toggleInput2(checkboxId, inputId) {
        var checkbox = document.getElementById(checkboxId);
        var input = document.getElementById(inputId);

        if (checkbox.checked) {
            input.style.display = "none";
            input.required = false;
        } else if(!checkbox.checked){
            input.style.display = "block";
            input.required = true;
        }
    }
</script>

                </div>
        </section>
        </main>
<?=footersiswa()?>
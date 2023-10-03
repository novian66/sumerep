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
$id_kelas = $_GET['kelas'];

$stmts3 = $pdo->prepare("SELECT * FROM `question_categories` WHERE role_id = '2'");
$stmts3->execute();
// Fetch the records so we can display them in our template.
$rows3 = $stmts3->fetchAll(PDO::FETCH_ASSOC);

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
                                       value="<?=$answer2?>">
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
                                       style="display: none">
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
                                        <input type="text" name="input<?=$nomors?>" value="<?=$answer2?>" class="form-control">
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
                        <?php } } ?>
                        </div>
                        <?php } ?>
                        <a href="list-siswa-guru.php?kelas=<?=$id_kelas?>" class="btn btn-sm btn-rounded btn-warning">Kembali</a>
            
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
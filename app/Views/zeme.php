<?= $this->extend("layout/template") ?>
<?= $this->section("content") ?>
<H1 class="pt-5 ">Přehled stanic ze země: <?= $bundesland->name;   ?></H1>

<div class="row">
    <?php
    foreach ($stanice as $row) {
        ?>
        <div class="card col-4 me-5 mb-3">
            <div class="card-body">
                <?php 
                    echo "<h3>".anchor("data/".$row->S_ID, $row->place)."</h3>";
                    echo "<p><b>Nadmořská výška: </b>".$row->height." mnm</p>";
                    echo "<p><b>zeměpisný šířka: </b>".$row->geo_latitude." ° </p>";
                    echo "<p><b>zeměpisná délka: </b>".$row->geo_longtitude." ° </p>";
                
                ?>
            </div>
        </div>
        <?php
    } 
?>

</div>


<?= $this->endSection() ?>
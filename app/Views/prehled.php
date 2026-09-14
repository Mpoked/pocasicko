<?= $this->extend("layout/template") ?>
<?= $this->section("content") ?>
<div class="row">
    <?php
    foreach ($prehled as $row) {
        ?>
        <div class="card col-4 me-5 mb-3">
            <div class="card-body">
                <?php 
                $data = [
                    'src' => base_url("obrazky/vlajky/".$row->vlajka),
                    'alt' => "vlajka",
                    'class' => 'img-fluid'
                ];
                    echo img($data);
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

<div class="justify-content-center">
<?= $pager->links(); ?>
</div>
</div>

<?= $this->endSection() ?>
<?= $this->extend("layout/template") ?>
<?= $this->section("content") ?>
<div class="row pt-3">
    <h2>Info o <?= $zeme->name ?></h2>
    <div class="col-6">
        <img class="img-fluid" src="<?= base_url("obrazky/vlajky/".$zeme->vlajka)?>" alt="">
    </div>
    <div class="col-6">
    <img class="img-fluid" src="<?= base_url("obrazky/mapy/".$zeme->mapa)?>" alt="">
    </div>
</div>

<?= $this->endSection() ?>
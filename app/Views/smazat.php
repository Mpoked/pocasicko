<?= $this->extend("layout/template") ?>
<?= $this->section("content") ?>

<div class="row pt-3">
    <div class="offset-2 col-6">
        <h1>Smazat data – <?= esc($stanice->place) ?></h1>

        <?php if (session()->getFlashdata("zprava")): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata("zprava")) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata("chyba")): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata("chyba")) ?></div>
        <?php endif; ?>

        <?php if (empty($mesice)): ?>
            <p>Stanice nemá žádná data ke smazání.</p>
        <?php else: ?>
            <?= form_open("smazat/".$stanice->S_ID, ["onsubmit" => "return confirm('Opravdu smazat data za vybraný měsíc?');"]) ?>
                <div class="mb-3">
                    <label for="obdobi" class="form-label">Měsíc</label>
                    <select name="obdobi" id="obdobi" class="form-select">
                        <?php foreach ($mesice as $m): ?>
                            <option value="<?= sprintf("%04d-%02d", $m->rok, $m->mesic) ?>">
                                <?= $m->mesic ?>/<?= $m->rok ?> (<?= $m->pocet ?> záznamů)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Smazat</button>
            <?= form_close() ?>
        <?php endif; ?>

        <p class="mt-3"><?= anchor("data/".$stanice->S_ID, "Zpět na data stanice") ?></p>
    </div>
</div>

<?= $this->endSection() ?>

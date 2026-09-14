<?= $this->extend("layout/template") ?>
<?= $this->section("content") ?>

<div class="row pt-3">
    <div class="offset-2 col-6">
        <h1><?= $zeme->place ?></h1>
        <p><?= anchor("smazat/".$zeme->S_ID, "Smazat data za měsíc", ["class" => "btn btn-outline-danger btn-sm"]) ?></p>
        <?php
        $table = new \CodeIgniter\View\Table();
        $table->setHeading( "Minimální teplota", "Maximální teplota", "průměrná teplota", "průměrný tlak vzduchu", "datum");

        foreach($udaje as $row) {
            $datum = strtotime($row->date);
            $datum = date("j.n.Y", $datum);
            $table->addRow( $row->min_2m, $row->max_2m, $row->mid_2m, $row->mid_air_pressure, $datum);
        }

        $template = array(
            'table_open'=> '<table class="table table-bordered">',
            'thead_open'=> '<thead>',
            'thead_close'=> '</thead>',
            'heading_row_start'=> '<tr>',
            'heading_row_end'=>' </tr>',
            'heading_cell_start'=> '<th>',
            'heading_cell_end' => '</th>',
            'tbody_open' => '<tbody>',
            'tbody_close' => '</tbody>',
            'row_start' => '<tr>',
            'row_end'  => '</tr>',
            'cell_start' => '<td>',
            'cell_end' => '</td>',
            'row_alt_start' => '<tr>',
            'row_alt_end' => '</tr>',
            'cell_alt_start' => '<td>',
            'cell_alt_end' => '</td>',
            'table_close' => '</table>'
            );
            
        $table->setTemplate($template);

        echo $table->generate();
        ?>
    </div>
</div>


<?= $this->endSection() ?>
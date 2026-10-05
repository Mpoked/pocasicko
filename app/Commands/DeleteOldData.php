<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\Data;

/**
 * Soft delete starých záznamů v tabulce data.
 * Spouští se cronem jednou za měsíc.
 */
class DeleteOldData extends BaseCommand
{
    protected $group       = 'Udrzba';
    protected $name        = 'data:cleanup';
    protected $description = 'Soft delete záznamů v tabulce data, které jsou starší než zadaný počet let (výchozí 11).';
    protected $usage       = 'data:cleanup [--years 11] [--dry-run]';
    protected $arguments   = [];
    protected $options     = [
        '--years'   => 'Kolik let se data ponechají. Výchozí: 11.',
        '--dry-run' => 'Jen vypíše, kolik záznamů by se smazalo. Nic nesmaže.',
    ];

    public function run(array $params)
    {
        $years = (int) ($params['years'] ?? CLI::getOption('years') ?? 11);
        if ($years < 1) {
            CLI::error('Parametr --years musí být celé číslo větší než 0.');

            return EXIT_ERROR;
        }

        $dryRun = array_key_exists('dry-run', $params) || CLI::getOption('dry-run');

        // hranice: starší záznamy než tento den se smažou
        $hranice = date('Y-m-d', strtotime('-' . $years . ' years'));

        $model = new Data();
        $pocet = $model->where('date <', $hranice)->countAllResults();

        CLI::write('Hranice: ' . $hranice . ' (data starší než ' . $years . ' let)');

        if ($pocet === 0) {
            CLI::write('Žádné záznamy ke smazání.', 'green');

            return EXIT_SUCCESS;
        }

        if ($dryRun) {
            CLI::write('Ke smazání: ' . $pocet . ' záznamů (--dry-run, nic nesmazáno).', 'yellow');

            return EXIT_SUCCESS;
        }

        // soft delete – nastaví deleted_at, záznamy v tabulce zůstanou
        $model->where('date <', $hranice)->delete();
        $smazano = $model->db->affectedRows();

        $zprava = 'Soft delete: smazáno ' . $smazano . ' záznamů starších než ' . $hranice . '.';
        CLI::write($zprava, 'green');
        log_message('info', '[data:cleanup] ' . $zprava);

        return EXIT_SUCCESS;
    }
}

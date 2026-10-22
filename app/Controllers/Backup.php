<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use ZipArchive;

class Backup extends BaseController
{
    public function index()
    {
        $this->requireRole([1]); // Only Super Admin

        $data['title']  = "Backup Database";
        $data['active'] = 'backup_db';

        return $this->renderView('backup/index', $data);
    }

    public function do_backup()
    {
        $this->requireRole([1]);

        $tables = $this->db->listTables();
        $sqlDump = "-- Mutases Database Backup\n";
        $sqlDump .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
        $sqlDump .= "-- PHP Version: " . phpversion() . "\n\n";
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sqlDump .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sqlDump .= "SET time_zone = \"+00:00\";\n\n";

        foreach ($tables as $table) {
            // Get CREATE TABLE
            $createTableQuery = $this->db->query("SHOW CREATE TABLE `{$table}`")->getRowArray();
            $createTableSql = $createTableQuery['Create Table'] ?? '';

            $sqlDump .= "\n-- --------------------------------------------------------\n";
            $sqlDump .= "-- Table structure for table `{$table}`\n";
            $sqlDump .= "-- --------------------------------------------------------\n\n";
            $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sqlDump .= $createTableSql . ";\n\n";

            // Get Table Data
            $rows = $this->db->table($table)->get()->getResultArray();
            if (!empty($rows)) {
                $sqlDump .= "-- Dumping data for table `{$table}`\n";
                $columns = array_keys($rows[0]);
                $escapedColumns = array_map(fn($col) => "`" . str_replace("`", "``", $col) . "`", $columns);
                $colList = implode(', ', $escapedColumns);

                $batchSize = 200;
                $totalRows = count($rows);

                for ($i = 0; $i < $totalRows; $i += $batchSize) {
                    $batch = array_slice($rows, $i, $batchSize);
                    $sqlDump .= "INSERT INTO `{$table}` ({$colList}) VALUES\n";

                    $valueLines = [];
                    foreach ($batch as $row) {
                        $values = [];
                        foreach ($row as $val) {
                            if ($val === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = $this->db->escape($val);
                            }
                        }
                        $valueLines[] = "(" . implode(', ', $values) . ")";
                    }
                    $sqlDump .= implode(",\n", $valueLines) . ";\n";
                }
                $sqlDump .= "\n";
            }
        }

        $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $dateStr = date('Y-m-d_H-i-s');
        $zipFileName = "backup-db-{$dateStr}.zip";
        $sqlFileName = "backup-db-{$dateStr}.sql";

        // Create Zip in memory or temp file
        $tempZip = tempnam(sys_get_temp_dir(), 'db_backup_');
        $zip = new ZipArchive();
        if ($zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFromString($sqlFileName, $sqlDump);
            $zip->close();
            $zipData = file_get_contents($tempZip);
            @unlink($tempZip);

            return $this->response->download($zipFileName, $zipData);
        }

        // Fallback to direct .sql download if zip fails
        return $this->response->download($sqlFileName, $sqlDump);
    }

    public function restore()
    {
        $this->requireRole([1]);

        $data['title']  = "Restore Database";
        $data['active'] = 'restore_db';

        return $this->renderView('backup/restore', $data);
    }

    public function do_restore()
    {
        $this->requireRole([1]);

        $file = $this->request->getFile('file_sql');

        if (!$file || !$file->isValid()) {
            $this->session->setFlashdata('error', 'File SQL tidak berhasil diupload.');
            return redirect()->to(base_url('backup/restore'));
        }

        $ext = strtolower($file->getClientExtension());
        $tmp = $file->getTempName();

        if (!file_exists($tmp) || filesize($tmp) === 0) {
            $this->session->setFlashdata('error', 'Gagal membaca file SQL!');
            return redirect()->to(base_url('backup/restore'));
        }

        $sqlContent = '';
        if ($ext === 'zip') {
            $zip = new ZipArchive();
            if ($zip->open($tmp) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entryName = $zip->getNameIndex($i);
                    if (str_ends_with(strtolower($entryName), '.sql')) {
                        $sqlContent = $zip->getFromIndex($i);
                        break;
                    }
                }
                $zip->close();
            }
        } else {
            $sqlContent = file_get_contents($tmp);
        }

        if (empty($sqlContent)) {
            $this->session->setFlashdata('error', 'Tidak ditemukan konten SQL di dalam file!');
            return redirect()->to(base_url('backup/restore'));
        }

        try {
            $mysqli = $this->db->connID;
            $mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

            // Split SQL into individual statements
            $templine = '';
            $lines = explode("\n", $sqlContent);

            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '/*') || str_starts_with($trimmed, '#')) {
                    continue;
                }

                $templine .= $line . "\n";

                if (str_ends_with(rtrim($trimmed), ';')) {
                    $mysqli->query($templine);
                    $templine = '';
                }
            }

            if (!empty(trim($templine))) {
                $mysqli->query($templine);
            }

            $mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

            $this->session->setFlashdata('success', 'Database berhasil direstore!');
        } catch (\Throwable $e) {
            $this->session->setFlashdata('error', 'Gagal merestore database: ' . $e->getMessage());
        }

        return redirect()->to(base_url('backup/restore'));
    }
}

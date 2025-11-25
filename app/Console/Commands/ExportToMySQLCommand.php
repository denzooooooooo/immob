<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportToMySQLCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export-mysql 
                            {--file=database/mysql_export.sql : Le fichier de sortie}
                            {--with-structure : Inclure la structure des tables}
                            {--with-data : Inclure les données}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporte la base de données SQLite vers un fichier SQL MySQL compatible';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Export de la base de données vers MySQL...');

        $filename = $this->option('file');
        $withStructure = $this->option('with-structure');
        $withData = $this->option('with-data');

        // Si aucune option spécifiée, inclure les deux
        if (!$withStructure && !$withData) {
            $withStructure = true;
            $withData = true;
        }

        try {
            $sql = $this->generateMySQLDump($withStructure, $withData);
            
            // Créer le dossier si nécessaire
            $directory = dirname($filename);
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            File::put($filename, $sql);

            $this->info("✅ Export terminé : {$filename}");
            $this->info("📊 Taille du fichier : " . $this->formatBytes(File::size($filename)));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'export : " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Génère le dump MySQL
     */
    protected function generateMySQLDump(bool $withStructure, bool $withData): string
    {
        $sql = "-- Export MySQL pour Monnkama\n";
        $sql .= "-- Généré le : " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Base de données : " . config('database.connections.mysql.database') . "\n\n";

        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET AUTOCOMMIT = 0;\n";
        $sql .= "START TRANSACTION;\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";

        // Obtenir toutes les tables
        $tables = $this->getTables();

        foreach ($tables as $table) {
            $this->info("Traitement de la table : {$table}");

            if ($withStructure) {
                $sql .= $this->getTableStructure($table);
            }

            if ($withData) {
                $sql .= $this->getTableData($table);
            }
        }

        $sql .= "\nSET FOREIGN_KEY_CHECKS = 1;\n";
        $sql .= "COMMIT;\n";

        return $sql;
    }

    /**
     * Obtient la liste des tables
     */
    protected function getTables(): array
    {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        return array_map(fn($table) => $table->name, $tables);
    }

    /**
     * Génère la structure d'une table pour MySQL
     */
    protected function getTableStructure(string $table): string
    {
        $sql = "\n-- Structure de la table `{$table}`\n";
        $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

        // Obtenir la structure SQLite
        $pragma = DB::select("PRAGMA table_info({$table})");
        
        $sql .= "CREATE TABLE `{$table}` (\n";
        $columns = [];

        foreach ($pragma as $column) {
            $columnSql = "  `{$column->name}` ";
            
            // Conversion des types SQLite vers MySQL
            $type = $this->convertSQLiteTypeToMySQL($column->type);
            $columnSql .= $type;

            // NOT NULL
            if ($column->notnull) {
                $columnSql .= " NOT NULL";
            }

            // DEFAULT
            if ($column->dflt_value !== null) {
                if ($column->dflt_value === 'CURRENT_TIMESTAMP') {
                    $columnSql .= " DEFAULT CURRENT_TIMESTAMP";
                } elseif (is_string($column->dflt_value)) {
                    $columnSql .= " DEFAULT '" . addslashes($column->dflt_value) . "'";
                } else {
                    $columnSql .= " DEFAULT {$column->dflt_value}";
                }
            }

            // AUTO_INCREMENT pour les clés primaires
            if ($column->pk && $type === 'bigint(20) UNSIGNED') {
                $columnSql .= " AUTO_INCREMENT";
            }

            $columns[] = $columnSql;
        }

        $sql .= implode(",\n", $columns);

        // Ajouter la clé primaire
        $primaryKey = array_filter($pragma, fn($col) => $col->pk);
        if (!empty($primaryKey)) {
            $pkColumns = array_map(fn($col) => "`{$col->name}`", $primaryKey);
            $sql .= ",\n  PRIMARY KEY (" . implode(', ', $pkColumns) . ")";
        }

        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        return $sql;
    }

    /**
     * Convertit les types SQLite vers MySQL
     */
    protected function convertSQLiteTypeToMySQL(string $sqliteType): string
    {
        $sqliteType = strtoupper($sqliteType);

        $typeMap = [
            'INTEGER' => 'bigint(20) UNSIGNED',
            'TEXT' => 'text',
            'REAL' => 'double',
            'BLOB' => 'longblob',
            'NUMERIC' => 'decimal(10,2)',
            'VARCHAR' => 'varchar(255)',
            'DATETIME' => 'timestamp',
            'TIMESTAMP' => 'timestamp',
            'BOOLEAN' => 'tinyint(1)',
            'TINYINT' => 'tinyint(4)',
            'SMALLINT' => 'smallint(6)',
            'MEDIUMINT' => 'mediumint(9)',
            'BIGINT' => 'bigint(20)',
            'DECIMAL' => 'decimal(10,2)',
            'FLOAT' => 'float',
            'DOUBLE' => 'double',
            'DATE' => 'date',
            'TIME' => 'time',
            'YEAR' => 'year(4)',
            'CHAR' => 'char(255)',
            'BINARY' => 'binary(255)',
            'VARBINARY' => 'varbinary(255)',
            'TINYBLOB' => 'tinyblob',
            'MEDIUMBLOB' => 'mediumblob',
            'LONGBLOB' => 'longblob',
            'TINYTEXT' => 'tinytext',
            'MEDIUMTEXT' => 'mediumtext',
            'LONGTEXT' => 'longtext',
            'ENUM' => 'enum',
            'SET' => 'set'
        ];

        // Gestion des types avec paramètres
        if (preg_match('/^(\w+)\(([^)]+)\)/', $sqliteType, $matches)) {
            $baseType = $matches[1];
            $params = $matches[2];

            if ($baseType === 'VARCHAR') {
                return "varchar({$params})";
            }
            if ($baseType === 'CHAR') {
                return "char({$params})";
            }
            if ($baseType === 'DECIMAL') {
                return "decimal({$params})";
            }
        }

        return $typeMap[$sqliteType] ?? 'text';
    }

    /**
     * Génère les données d'une table
     */
    protected function getTableData(string $table): string
    {
        $data = DB::table($table)->get();
        
        if ($data->isEmpty()) {
            return "-- Aucune donnée pour la table `{$table}`\n\n";
        }

        $sql = "-- Données de la table `{$table}`\n";
        $sql .= "INSERT INTO `{$table}` (";

        // Obtenir les colonnes
        $columns = array_keys((array) $data->first());
        $sql .= "`" . implode("`, `", $columns) . "`";
        $sql .= ") VALUES\n";

        $values = [];
        foreach ($data as $row) {
            $rowValues = [];
            foreach ($columns as $column) {
                $value = $row->$column;
                
                if ($value === null) {
                    $rowValues[] = 'NULL';
                } elseif (is_string($value)) {
                    $rowValues[] = "'" . addslashes($value) . "'";
                } elseif (is_bool($value)) {
                    $rowValues[] = $value ? '1' : '0';
                } else {
                    $rowValues[] = $value;
                }
            }
            $values[] = "(" . implode(", ", $rowValues) . ")";
        }

        $sql .= implode(",\n", $values) . ";\n\n";

        return $sql;
    }

    /**
     * Formate la taille en bytes
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

<?php
$dbName = 'nwplv2';
$backupDir = "C:/Repos/NWP_LV/LV2/Backup/$dbName";
$timestamp = time();
$mysqli = new mysqli('localhost', 'root', '', $dbName);

if ($mysqli->connect_error) {
    die("<p>Connection failed: " . $mysqli->connect_error . "</p>");
}

if (!is_dir($backupDir) && !mkdir($backupDir, 0777, true)) {
    die("<p>Failed to create backup directory.</p>");
}

echo "<p>Starting backup of '$dbName'...</p>";
$result = $mysqli->query('SHOW TABLES');

while ($row = $result->fetch_array(MYSQLI_NUM)) {
    if ($result != null) {
        $table = $row[0];
        $backupFile = "$backupDir/{$table}_{$timestamp}.txt";
        $gzFile = "$backupDir/{$table}_{$timestamp}.sql.gz";
        
        if ($tableData = $mysqli->query("SELECT * FROM $table")) {
            $columns = array_map(fn($col) => $col->name, $tableData->fetch_fields());
            
            if ($tableData->num_rows > 0) {
                $fp = fopen($backupFile, 'w');
                if (!$fp) {
                    echo "<p>Failed to create file: $backupFile</p>";
                    continue;
                }
                
                while ($row = $tableData->fetch_assoc()) {
                    $values = array_map(fn($val) => "'" . addslashes($val) . "'", array_values($row));
                    $query = "INSERT INTO $table (" . implode(", ", $columns) . ")\nVALUES (" . implode(", ", $values) . ");\n";
                    fwrite($fp, $query);
                }
                fclose($fp);
                echo "<p>Backup of table '$table' completed.</p>";
                
                $gz = gzopen($gzFile, 'w9');
                if ($gz) {
                    gzwrite($gz, file_get_contents($backupFile));
                    gzclose($gz);
                    unlink($backupFile);
                    echo "<p>Compressed backup created: $gzFile</p>";
                } else {
                    echo "<p>Failed to create compressed file: $gzFile</p>";
                }
            }
        }
    }
    else {
        echo "<p>Database $dbName is empty</p>";
        return;
    }
}

$mysqli->close();
echo "<p>Backup process completed.</p>";
?>
<?php
function handle_open_element($parser, $element, $attributes) {
    switch ($element) {
        case 'RECORD':
            echo '<div class="profile" style="border: 1px solid #0000FF; padding: 10px; margin-bottom: 20px;">';
            break;

        case 'SLIKA':
            echo '<h3>Slika:</h3>';
            break;

        case 'IME':
        case 'PREZIME':
        case 'EMAIL':
        case 'ZIVOTOPIS':
        case 'SPOL':
            echo '<h4>' . ucfirst(strtolower($element)) . ': </h4>';
            break;
    }
}

function handle_close_element($parser, $element) {
    switch ($element) {
        case 'RECORD':
            echo '</div>';  
            break;

        case 'IME':
        case 'PREZIME':
        case 'EMAIL':
        case 'ZIVOTOPIS':
            echo '<br>';
            break;

        case 'SLIKA':
            break;
    }
}

function handle_character_data($parser, $data) {
    echo htmlspecialchars($data);  
}

$parser = xml_parser_create();
xml_set_element_handler($parser, 'handle_open_element', 'handle_close_element');
xml_set_character_data_handler($parser, 'handle_character_data');

$file = 'file.xml';
$fp = @fopen($file, 'r') or die("<p>Ne možemo otvoriti datoteku '$file'.</p>");

while ($data = fread($fp, 4096)) {
    if (!xml_parse($parser, $data, feof($fp))) {
        die(sprintf("XML parsing error: %s at line %d", xml_error_string(xml_get_error_code($parser)), xml_get_current_line_number($parser)));
    }
}

xml_parser_free($parser);
fclose($fp);
?>

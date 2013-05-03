<?php
if ($handle = opendir('.')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            echo "<filename>images/$file</filename>\n";
        }
    }
    closedir($handle);
}
?>

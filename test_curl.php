<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://repo.packagist.org/packages.json");
curl_setopt($ch, CURLOPT_CAINFO, "C:\\xampp\\php\\extras\\ssl\\cacert.pem");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
$response = curl_exec($ch);
if ($response === false) {
    echo "cURL Error: " . curl_error($ch) . " (Error Code: " . curl_errno($ch) . ")";
} else {
    echo "Success: HTTP " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
}
curl_close($ch);
?>
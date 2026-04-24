<?php
// Saves an uploaded profile file and returns the relative public path.
function saveUploadedProfile($fieldName, &$message) {
    // Treats an empty file input as no file selected.
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]["error"] === UPLOAD_ERR_NO_FILE) {
        return "";
    }

    // Stops when PHP reports an upload error.
    if ($_FILES[$fieldName]["error"] !== UPLOAD_ERR_OK) {
        $message = "Error uploading file.";
        return false;
    }

    // Rejects files larger than the app's upload limit.
    if ($_FILES[$fieldName]["size"] > 5 * 1024 * 1024) {
        $message = "Uploaded file must be 5MB or smaller.";
        return false;
    }

    // Extracts the extension and blocks executable upload types.
    $originalName = $_FILES[$fieldName]["name"];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $blockedExtensions = ["php", "phtml", "phar", "htaccess"];

    if ($extension === "" || in_array($extension, $blockedExtensions, true)) {
        $message = "Uploaded file type is not allowed.";
        return false;
    }

    // Ensures the public uploads directory exists before saving.
    $uploadDir = __DIR__ . "/../../public/uploads";
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
        $message = "Could not create upload directory.";
        return false;
    }

    // Builds a safe unique filename from the original upload name.
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
    $safeBaseName = preg_replace("/[^A-Za-z0-9_-]/", "_", $baseName);
    if ($safeBaseName === "") {
        $safeBaseName = "upload";
    }

    $uniqueId = str_replace(".", "", uniqid("", true));
    $fileName = $safeBaseName . "_" . $uniqueId . "." . $extension;
    $targetPath = $uploadDir . "/" . $fileName;

    // Moves the temporary upload into the public uploads directory.
    if (!move_uploaded_file($_FILES[$fieldName]["tmp_name"], $targetPath)) {
        $message = "Could not save uploaded file.";
        return false;
    }

    // Returns the relative path that should be stored in the database.
    return "public/uploads/" . $fileName;
}
?>

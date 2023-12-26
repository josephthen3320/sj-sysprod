<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signatureData'])) {
    $signatureData = $_POST['signatureData'];

    //var_dump($signatureData);

    // Send a JSON response with the signature data
    $response = array(
        'status' => 'success',
        'signatureData' => $signatureData
    );
    header('Content-Type: application/json');
    echo json_encode($response);


/*
    $sql = "INSERT INTO signatures (signature_data) VALUES ('$signatureData')";

    if ($conn->query($sql) === TRUE) {
        // Signature data saved successfully
        http_response_code(200);
        echo 'Signature saved successfully!';
    } else {
        // Error occurred while saving the signature data
        http_response_code(500);
        echo 'Error saving the signature. Please try again.';
    }

    $conn->close();
*/
}
?>

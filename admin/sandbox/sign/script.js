const canvas = document.getElementById('signatureCanvas');
const ctx = canvas.getContext('2d');
let drawing = false;

function startDrawing(e) {
    drawing = true;
    draw(e);
}

function stopDrawing() {
    drawing = false;
}

function draw(e) {
    if (!drawing) return;

    const x = e.offsetX;
    const y = e.offsetY;

    ctx.lineWidth = 2;
    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
}

function resetSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.beginPath();
}

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mouseout', stopDrawing);

const saveButton = document.getElementById('saveButton');
saveButton.addEventListener('click', () => {
    const signatureData = canvas.toDataURL(); // Convert canvas data to base64 image data

// Send the signature data to the server via AJAX
const xhr = new XMLHttpRequest();
xhr.open('POST', 'save_signature.php', true);
xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                // Signature saved successfully
                alert('Signature saved successfully!');

                // Display the signature image on the page
                const signatureImage = document.createElement('img');
                signatureImage.src = response.signatureData;
                document.body.appendChild(signatureImage);
            } else {
                // Error occurred while saving the signature
                alert('Error saving the signature. Please try again.');
            }
        } else {
            // AJAX request failed
            alert('Error occurred during the AJAX request.');
        }
    }
};
xhr.send(`signatureData=${signatureData}`);
});

const resetButton = document.getElementById('resetButton');
resetButton.addEventListener('click', () => {
    resetSignature();
});
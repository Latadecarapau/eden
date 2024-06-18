function showModal(reservationId) {
    document.getElementById('cancelModal').style.display = 'block';
    document.getElementById('reservationIdInput').value = reservationId;
}

function closeModal() {
    document.getElementById('cancelModal').style.display = 'none';
}

function confirmCancellation() {
    const reservationId = document.getElementById('reservationIdInput').value;

    if (!reservationId) {
        alert('Please enter the reservation ID.');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "Reservas.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Request completed with status:", xhr.status);
            console.log("Response:", xhr.responseText);
            if (xhr.status === 200) {
                alert(xhr.responseText);
                location.reload(); // reload para atualizar a tabela
            } else {
                alert("Error: Unable to cancel reservation.");
            }
        }
    };
    console.log("Sending request with reservation ID:", reservationId);
    xhr.send("id_reservation=" + reservationId);
}

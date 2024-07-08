function showPopup(message) {
    document.getElementById('popup').style.display = 'block';
    document.getElementById('message').textContent = message;
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';

}
<!-- Overlay pour le fond sombre -->
<div id="overlay" class="overlay"></div>

<!-- Container pour les popups d'alerte -->
<div id="custom-alert" class="custom-alert">
    <div class="alert-content">
        <p id="alert-message"></p>
        <button id="alert-ok" onclick="closeAlert()">OK</button>
    </div>
</div>

<style>
/* Style pour les alertes personnalisées */
.custom-alert {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 300px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    z-index: 1002;
    overflow: hidden;
}

.alert-content {
    padding: 20px;
    text-align: center;
}

.alert-content p {
    margin-bottom: 20px;
    font-size: 16px;
    color: #333;
}

.alert-content button {
    background-color: #44f6ff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
}

.alert-content button:hover {
    background-color: #65edff;
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1001;
}
</style>

<script>
function showCustomAlert(message, callback) {
    document.getElementById('alert-message').textContent = message;
    document.getElementById('custom-alert').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
    
    // Si un callback est fourni, l'ajouter au bouton OK
    if (callback) {
        document.getElementById('alert-ok').onclick = function() {
            closeAlert();
            callback();
        };
    }
}

function closeAlert() {
    document.getElementById('custom-alert').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}
</script>
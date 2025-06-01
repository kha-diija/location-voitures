<?php

session_start();
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour annuler une réservation.']);
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$reservation_id = $_POST['reservation_id'] ?? 0;

// Vérifier que la réservation existe et appartient à l'utilisateur
$stmt = $conn->prepare("SELECT * FROM Reservation WHERE num_reser = ? AND id_client = ?");
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Réservation non trouvée ou vous n\'êtes pas autorisé à l\'annuler.']);
    exit;
}

$reservation = $result->fetch_assoc();
$date_debut = new DateTime($reservation['date_debut']);
$now = new DateTime();

// Vérifier que la date de début est dans le futur (on ne peut pas annuler une réservation passée)
if ($date_debut <= $now) {
    echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas annuler une réservation déjà commencée ou passée.']);
    exit;
}

// Calculer le remboursement (avec pénalité de 20%)
$tarif = $reservation['tarif'];
$remboursement = $tarif * 0.8; // 80% du tarif (pénalité de 20%)
$penalite = $tarif * 0.2; // 20% du tarif

// Mettre à jour le statut de la réservation et le montant remboursé
// Supprimer la réservation
$stmt = $conn->prepare("DELETE FROM Reservation WHERE num_reser = ?");
if (!$stmt) {
    throw new Exception('Erreur de préparation de la requête de suppression: ' . $conn->error);
}

$stmt->bind_param("i", $reservation_id);
if (!$stmt->execute()) {
    throw new Exception('Erreur lors de la suppression de la réservation: ' . $stmt->error);
}


if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Réservation annulée avec succès. Un remboursement de ' . number_format($remboursement, 2) . ' DH sera effectué.',
        'remboursement' => $remboursement,
        'penalite' => $penalite
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'annulation de la réservation: ' . $conn->error]);
}

$conn->close();
?>
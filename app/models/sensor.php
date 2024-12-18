<?php
/**
 * Classe Sensor pour gérer les capteurs globaux et d'intrusion.
 */

require_once __DIR__ . '/../../config.php';

class Sensor {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDOConnection();
    }

    // Récupérer tous les capteurs
    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT C.id_capteur, C.nom_capteur, C.statut, CI.emplacement, CI.niveau_alerte
            FROM capteurs AS C
            LEFT JOIN capteurs_intrusion AS CI ON C.id_capteur = CI.id_capteur
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un capteur
    public function add($nom_capteur, $type_capteur, $departement) {
        $stmt = $this->pdo->prepare("
            INSERT INTO capteurs (nom_capteur, type_capteur, departement) 
            VALUES (:nom_capteur, :type_capteur, :departement)
        ");
        $stmt->execute([
            'nom_capteur' => $nom_capteur,
            'type_capteur' => $type_capteur,
            'departement' => $departement
        ]);
    }

    // Supprimer un capteur
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM capteurs WHERE id_capteur = :id");
        $stmt->execute(['id' => $id]);
    }
}

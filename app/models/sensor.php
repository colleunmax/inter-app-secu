<?php
require_once __DIR__ . '/../../config.php';

class Sensor {
    private $pdo_security;
    private $pdo_smartcity;

    public function __construct() {
        // Connexions aux deux bases de données
        $this->pdo_security = getSecurityConnection();
        $this->pdo_smartcity = getSmartcityConnection();
    }

    public function getAll() {
        $stmt = $this->pdo_security->query("SELECT id_capteur, emplacement, niveau_alerte FROM capteurs_intrusion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($nom) {
        // Vérification avec regex
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,20}$/u', $nom)) {
            throw new Exception("Le nom doit contenir uniquement des lettres et être limité à 20 caractères.");
        }

        try {
            // Étape 1 : Insérer dans la base "smartcity_db"
            $stmt = $this->pdo_smartcity->prepare("
                INSERT INTO capteurs (nom_capteur, departement, statut, type_capteur)
                VALUES (:nom, 'Sécurité', 3, 3)
            ");
            $stmt->execute(['nom' => $nom]);

            // Récupérer l'ID auto-incrémenté
            $idCapteur = $this->pdo_smartcity->lastInsertId();

            // Étape 2 : Insérer dans la base "security_db" avec le même ID
            $stmtSecurity = $this->pdo_security->prepare("
                INSERT INTO capteurs_intrusion (id_capteur, emplacement, niveau_alerte, date_signalement)
                VALUES (:id, :emplacement, 0, NOW())
            ");
            $stmtSecurity->execute([
                'id' => $idCapteur,
                'emplacement' => $nom
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout du capteur : " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            // Supprimer de la table capteurs_intrusion
            $stmt = $this->pdo_security->prepare("
                DELETE FROM capteurs_intrusion WHERE id_capteur = :id
            ");
            $stmt->execute(['id' => $id]);

            // Supprimer également de la table capteurs dans "smartcity_db"
            $stmtSmartcity = $this->pdo_smartcity->prepare("
                DELETE FROM capteurs WHERE id_capteur = :id
            ");
            $stmtSmartcity->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression du capteur : " . $e->getMessage());
        }
    }      
}
?>

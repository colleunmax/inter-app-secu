<?php
/**
 * capteur.php
 *
 * Ce fichier gère les capteurs d'intrusion dans le système.
 * Il contient des fonctions pour ajouter, modifier, supprimer et surveiller les capteurs.
 * Les capteurs sont d'abord créés en auto-incrémentation dans la base de données globale, puis créés 
 * localement avec leur ID dans la table globale.
 * Les capteurs sont connectés à des alertes globales en fonction de leur niveau d'alerte.
 * Ce fichier utilise deux connexions à la base de données (sécurité et smartcity).
 */

require_once 'alert.php'; 

class Capteur {
    private $pdo_security;
    private $pdo_smartcity;
    private $alert;

    public function __construct($pdo_security, $pdo_smartcity) {
        $this->pdo_security = $pdo_security;
        $this->pdo_smartcity = $pdo_smartcity;
        $this->alert = new Alert($pdo_security, $pdo_smartcity); 
    }

    public function getAll() {
        $stmt = $this->pdo_security->query("SELECT id_capteur, emplacement, niveau_alerte FROM capteurs_intrusion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($nom) {
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,20}$/u', $nom)) {
            throw new Exception("Le nom doit contenir uniquement des lettres et être limité à 20 caractères.");
        }

        try {
            $stmt = $this->pdo_smartcity->prepare("
                INSERT INTO Capteurs (nom_capteur, departement, statut, type_capteur)
                VALUES (:nom, 'Sécurité', 3, 3)
            ");
            $stmt->execute(['nom' => $nom]);

            $idCapteur = $this->pdo_smartcity->lastInsertId();

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
            $stmt = $this->pdo_security->prepare("
                DELETE FROM capteurs_intrusion WHERE id_capteur = :id
            ");
            $stmt->execute(['id' => $id]);

            $stmtSmartcity = $this->pdo_smartcity->prepare("
                DELETE FROM Capteurs WHERE id_capteur = :id
            ");
            $stmtSmartcity->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression du capteur : " . $e->getMessage());
        }
    }

    public function updateAlertLevel($id, $level) {
        try {
            $stmt = $this->pdo_security->prepare("
                UPDATE capteurs_intrusion SET niveau_alerte = :niveau_alerte WHERE id_capteur = :id
            ");
            $stmt->execute([
                'niveau_alerte' => $level,
                'id' => $id
            ]);

            if ($level > 0) {
                $stmtSensor = $this->pdo_security->prepare("
                    SELECT emplacement FROM capteurs_intrusion WHERE id_capteur = :id
                ");
                $stmtSensor->execute(['id' => $id]);
                $sensor = $stmtSensor->fetch(PDO::FETCH_ASSOC);

                if ($sensor) {
                    $this->alert->createGlobalAlert($id, "Capteur actif à l'emplacement : " . $sensor['emplacement']);
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour du niveau d'alerte : " . $e->getMessage());
        }
    }
}
?>
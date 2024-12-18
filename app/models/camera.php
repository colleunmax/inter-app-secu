<?php
/**
 * Classe Camera pour gérer les caméras.
 */

 require_once __DIR__ . '/../../config.php';
 
 class Camera {
     private $pdo;
 
     public function __construct() {
         $this->pdo = getPDOConnection();
     }
 
     // Récupérer toutes les caméras
     public function getAll() {
         $stmt = $this->pdo->query("SELECT id_camera, emplacement, statut, date_maj FROM caméras");
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
 
     // Ajouter une caméra avec statut et date automatique
     public function add($emplacement) {
         $stmt = $this->pdo->prepare("
             INSERT INTO caméras (emplacement, statut, date_maj) 
             VALUES (:emplacement, 1, NOW())
         ");
         $stmt->execute(['emplacement' => $emplacement]);
     }
 
     // Supprimer une caméra
     public function delete($id) {
         $stmt = $this->pdo->prepare("DELETE FROM caméras WHERE id_camera = :id");
         $stmt->execute(['id' => $id]);
     }
 }

 ?>
 
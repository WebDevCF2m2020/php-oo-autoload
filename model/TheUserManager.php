<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TheUserManager
 *
 * @author Family PITZ
 */
class TheUserManager extends ManagerAbstract implements ManagerInterface {
    //put your code here
    public function getAll(): array {
        
    }
    
    // connexion à l'administration
    public function connectTheUser(TheUser $user): array{
        $sql = "SELECT u.`idtheUser`,u.`theUserLogin`,u.`theUserMail`,r.theRoleName,r.`theRoleValue`
    FROM theuser u  
    INNER JOIN therole r
        ON u.`theRoleIdtheRole`= r.idtheRole
    WHERE u.theUserLogin=? AND u.theUserPwd=?;";
        
        $prepare = $this->db->prepare($sql);
        
        try{
            // exécution de la requête préparée
            $prepare->execute([$user->getTheUserLogin(),$user->getTheUserPwd()]);
            
            // on a un résultat
            if($prepare->rowCount()){
                // récupartion du résultat de la requête
                $recup = $prepare->fetch(PDO::FETCH_ASSOC);
                // instanciation avec hydratation
                $TheUserInstance = new TheUser($recup);
                // ajout des valeurs externes à la table
                $TheUserInstance->theRoleName = $recup['theRoleName'];
                $TheUserInstance->theRoleValue = $recup['theRoleValue'];
                // la connexion est réussie
                return [0=>$TheUserInstance];
                
            }else{
                return [1=>"Votre login et/ou mot de passe est incorrecte"];
            }
            
        } catch (PDOException $ex) {
            return [2=>$ex->getMessage()];
        }
    }

}

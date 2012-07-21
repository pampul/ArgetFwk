<?php

require_once 'PdoConnect.php';

class AdminManager extends PdoConnect {

    public function getTable() {

        $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date_inscription, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_admin
                                         ORDER BY timestamp DESC");

        $query->execute();

        $result = array();

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $result[] = new Admin($row->id, $row->timestamp, $row->adminlvl, $row->fonction, $row->privilege, $row->nom, $row->prenom, $row->email, $row->password, $row->tel, $row->date_inscription, $row->image);
        }

        return $result;
    }

    public function getTableWithIdPublic($id) {

        $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date_inscription, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_admin
										WHERE id=:id
                                         ORDER BY timestamp DESC");
        $query->bindValue(':id', $id);
        $query->execute();

        $result = array();

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {

            return $result[] = new Admin($row->id, $row->timestamp, $row->adminlvl, $row->fonction, $row->privilege, $row->nom, $row->prenom, $row->email, $row->password, $row->tel, $row->date_inscription, $row->image);
        }
    }

    public function getTableWithLimitSortedOrdered($selection, $ordre, $premiereEntree, $messagesParPage) {

        $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date_inscription, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_admin
                                         ORDER BY " . $selection . " " . $ordre . "
									    LIMIT " . $premiereEntree . ", " . $messagesParPage . "");

        $query->execute();

        $result = array();

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $result[] = new Admin($row->id, $row->timestamp, $row->adminlvl, $row->fonction, $row->privilege, $row->nom, $row->prenom, $row->email, $row->password, $row->tel, $row->date_inscription, $row->image);
        }

        return $result;
    }

    public function add($user, $fonction, $privilege, $nom, $prenom, $email, $password, $tel, $image) {

        $time = time();

        $query = $this->pdo->prepare('INSERT INTO base_admin(timestamp, date_inscription, fonction, privilege, nom, prenom, email, password, tel, image)
									VALUES(:time, NOW(), :fonction, :privilege, :nom, :prenom, :email, :password, :tel, :image)');


        $query->bindValue(':time', $time);
        $query->bindValue(':fonction', $fonction);
        $query->bindValue(':privilege', $privilege);
        $query->bindValue(':nom', $nom);
        $query->bindValue(':prenom', $prenom);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);
        $query->bindValue(':tel', $tel);
        $query->bindValue(':image', $image);

        $logManager = new LogManager();
        $logManager->add($user->nom . " " . $user->prenom, "Ajout", "admin", $nom . " " . $prenom);

        return $query->execute();
    }

    public function update($user, $id, $fonction, $privilege, $nom, $prenom, $email, $password, $tel, $image) {


        $query = $this->pdo->prepare('UPDATE base_admin
								    SET fonction=:fonction, privilege=:privilege, nom=:nom, prenom=:prenom, email=:email, password=:password, tel=:tel, image=:image
									WHERE id=:id');

        $query->bindValue(':id', $id);
        $query->bindValue(':fonction', $fonction);
        $query->bindValue(':privilege', $privilege);
        $query->bindValue(':nom', $nom);
        $query->bindValue(':prenom', $prenom);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);
        $query->bindValue(':tel', $tel);
        $query->bindValue(':image', $image);

        $logManager = new LogManager();
        $logManager->add($user->nom . " " . $user->prenom, "Edition", "admin", $nom . " " . $prenom);

        return $query->execute();
    }

    public function updateUiEmailUser($ui, $email, $password) {

        $query = $this->pdo->prepare('UPDATE base_admin
				SET password=:password
				WHERE adminlvl=:adminlvl AND email=:email');

        $query->bindValue(':adminlvl', $ui);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);

        return $query->execute();
    }

    public function remove($user, $id) {


        $query = $this->pdo->prepare("DELETE FROM base_admin WHERE id=:id");
        $query->bindValue(':id', $id);

        $logManager = new LogManager();
        $logManager->add($user->nom . " " . $user->prenom, "Suppression", "admin", "-");

        return $query->execute();
    }

    public function count() {


        $row = 0;

        $query = $this->pdo->prepare("SELECT COUNT(*) as cpt FROM base_admin");

        $query->execute();

        $row = $query->fetch(PDO::FETCH_OBJ);

        if ($row)
            return $row->cpt;
        else
            return 0;
    }

    public function checkAdmin($login, $password) {

        $query = $this->pdo->prepare("SELECT * FROM base_admin WHERE email=:login AND password=:mdp");
        $query->bindValue(':login', $login);
        $query->bindValue(':mdp', md5($password));

        $query->execute();

        $row = $query->fetch(PDO::FETCH_OBJ);

        if ($row)
            return true;
        else
            return false;
    }

    public function checkEmail($login) {

        $query = $this->pdo->prepare("SELECT * FROM base_admin WHERE email=:login");
        $query->bindValue(':login', $login);

        $query->execute();

        $row = $query->fetch(PDO::FETCH_OBJ);

        if ($row)
            return true;
        else
            return false;
    }

    public function setUniqueID($email, $uniqueID) {

        $query = $this->pdo->prepare('UPDATE base_admin
				SET adminlvl=:uniqueid 
				WHERE email=:email');

        $query->bindValue(':email', $email);
        $query->bindValue(':uniqueid', $uniqueID);

        $logManager = new LogManager();
        $logManager->add("Demande reinitialisation MDP", "Reinit MDP", "admin", $email);

        return $query->execute();
    }

    public function checkEmailAndUI($uniqueID, $email) {

        $query = $this->pdo->prepare("SELECT * FROM base_admin WHERE email=:login && adminlvl=:ui");
        $query->bindValue(':login', $email);
        $query->bindValue(':ui', $uniqueID);

        $query->execute();

        $row = $query->fetch(PDO::FETCH_OBJ);

        if ($row)
            return true;
        else
            return false;
    }

    public function getTableWithLoginMdp($login, $mdp) {

        $query = $this->pdo->prepare("SELECT *, DATE_FORMAT(date_inscription, '%d/%m/%Y &agrave; %H:%i') AS date 
                                         FROM base_admin
                                         WHERE email=:email AND password=:password
                                         ORDER BY timestamp DESC");
        $query->bindValue(':email', $login);
        $query->bindValue(':password', md5($mdp));
        $query->execute();

        $result = array();

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {

            return $result[] = new Admin($row->id, $row->timestamp, $row->adminlvl, $row->fonction, $row->privilege, $row->nom, $row->prenom, $row->email, $row->password, $row->tel, $row->date_inscription, $row->image);
        }
    }

}

?>
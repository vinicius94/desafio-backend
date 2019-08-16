<?php
    namespace App;

    class MySQLConnection {
        private $pdo;
        private $errorMessage;

        public function connect() {

            //If not connected, try to connect
            if ($this->pdo == null) {
                try {
                    $this->pdo = new \PDO("mysql:host=".Config::HOST_NAME.";dbname=".Config::DB_NAME, Config::USERNAME, Config::PASSWORD);
                    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                } catch(\PDOException $exception) {
                    $this->errorMessage = $exception->getMessage();
                }
            }

            return $this->pdo;
        }

        public function getErrorMessage() {
            return $this->errorMessage;
        }
    }
?>
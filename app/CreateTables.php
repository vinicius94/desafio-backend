<?php

    namespace App;

    class CreateTables {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function create() {
            $commands = ["CREATE TABLE IF NOT EXISTS `product` (
                            `product_id` varchar(40) NOT NULL,
                            `artist` varchar(50) NOT NULL,
                            `year` year(4) NOT NULL,
                            `album` varchar(50) NOT NULL,
                            `price` decimal(10,2) NOT NULL,
                            `store` varchar(50) NOT NULL,
                            `thumb` varchar(100) NOT NULL,
                            `date` date NOT NULL,
                            PRIMARY KEY (`product_id`)
                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
                          "CREATE TABLE IF NOT EXISTS `cart` (
                            `cart_id` varchar(40) NOT NULL,
                            `client_id` varchar(40) NOT NULL,
                            `product_id` varchar(40) NOT NULL,
                            `date_time` timestamp NOT NULL,
                            PRIMARY KEY (`cart_id`,`client_id`,`product_id`),
                            FOREIGN KEY `fk_product` (`product_id`) REFERENCES `product` (`product_id`)
                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
                          "CREATE TABLE IF NOT EXISTS `transaction` (
                            `order_id` varchar(40) NOT NULL,
                            `client_id` varchar(40) NOT NULL,
                            `cart_id` varchar(40) NOT NULL,
                            `credit_id` smallint(4) NOT NULL,
                            `value` decimal(10,2) NOT NULL,
                            `date_transaction` date NOT NULL,
                            PRIMARY KEY (`order_id`),
                            FOREIGN KEY `fk_cart` (`cart_id`) REFERENCES `cart` (`cart_id`)
                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"];
            
            foreach($commands as $command) {
                $this->pdo->exec($command);
            }
        }

        public function getTableList() {
            $stmt = $this->pdo->query("SHOW TABLES");

            $tableList = array();

            while($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                $tableList[] = $row[0];
            }

            return $tableList;
        }
    }

?>
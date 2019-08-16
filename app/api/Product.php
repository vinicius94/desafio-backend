<?php

    namespace App\Api;

    class Product {
        public static function getListProducts($pdo) {
            $stmt = $pdo->query("SELECT product_id, artist, year, album, price, store, thumb, date FROM product");

            $results = array();
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $dt = \DateTime::createFromFormat('Y-m-d', $row["date"]);
                
                $row["date"] = $dt->format('d/m/Y');

                $results[] = $row;
            }

            return $results;
        }

        public static function saveProduct($pdo, $data) {
            $dt = \DateTime::createFromFormat('d/m/Y', $data["date"]);

            $stmt = $pdo->prepare("INSERT INTO product (product_id, artist, year, album, price, store, thumb, date) 
                                                VALUES (:product_id, :artist, :year, :album, :price, :store, :thumb, :date)");

            $stmt->execute(array(
                ":product_id" => $data["product_id"],
                ":artist" => $data["artist"],
                ":year" => $data["year"],
                ":album" => $data["album"],
                ":price" => $data["price"],
                ":store" => $data["store"],
                ":thumb" => $data["thumb"],
                ":date" => $dt->format('Y-m-d')
            ));

            return Product::getPersistedProduct($pdo, $data["product_id"]);
        }

        public static function getPersistedProduct($pdo, $id) {
            $stmt = $pdo->prepare("SELECT product_id, artist, year, album, price, store, thumb, date FROM product WHERE product_id = :id");
            $stmt->execute(array(
                ":id" => $id
            ));

            $results = array();
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $dt = \DateTime::createFromFormat('Y-m-d', $row["date"]);
                
                $row["date"] = $dt->format('d/m/Y');

                $results[] = $row;
            }

            return $results;
        }
    }
?>
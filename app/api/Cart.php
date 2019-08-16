<?php

    namespace App\Api;

    class Cart {
        public static function add($pdo, $data) {
            $time = explode(":", $data["time"]);

            $dt = \DateTime::createFromFormat('d/m/Y', $data["date"]);
            $dt->setTime($time[0], $time[1], $time[2]);

            $stmt = $pdo->prepare("INSERT INTO cart (cart_id, client_id, product_id, date_time) 
                                             VALUES (:cart_id, :client_id, :product_id, :date_time)");

            $stmt->execute(array(
                ":cart_id" => $data["cart_id"],
                ":product_id" => $data["product_id"],
                ":client_id" => $data["client_id"],
                ":date_time" => $dt->format('Y-m-d H:i:s')
            ));

            return Cart::getPersistedCart($pdo, $data["cart_id"]);
        }

        public static function getPersistedCart($pdo, $id) {
            $stmt = $pdo->prepare("SELECT cart_id, client_id, product_id, date_time FROM cart WHERE cart_id = :cart_id");
            $stmt->execute(array(
                ":cart_id" => $id
            ));

            $results = array();
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $item = $row;
                $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $item["date_time"]);

                $item["date"] = $dt->format('d/m/Y');
                $item["time"] = $dt->format('H:i:s');
                
                unset($item["date_time"]);

                $results[] = $item;
            }

            return $results;
        }
    }

?>
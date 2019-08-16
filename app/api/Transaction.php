<?php

    namespace App\Api;

    class Transaction {
        static public function saveTransaction($pdo, $data) {
            $stmt = $pdo->prepare("INSERT INTO transaction (order_id, client_id, cart_id, credit_id, value, date_transaction) 
                                                    VALUES (:order_id, :client_id, :cart_id, :credit_id, :value, :date_transaction)");

            $order_id = Transaction::generateGUID();

            $stmt->execute(array(
                ":order_id" => $order_id,
                ":client_id" => $data["client_id"],
                ":cart_id" => $data["cart_id"],
                ":value" => $data["value_to_pay"],
                ":credit_id" => substr($data["credit_card"]["number"], -4),
                ":date_transaction" => date("Y-m-d")
            ));

            return Transaction::getTransaction($pdo, $order_id);
        }

        static public function getTransaction($pdo, $order_id) {
            $stmt = $pdo->prepare("SELECT client_id, order_id, credit_id, value, date_transaction FROM transaction WHERE order_id = :order_id");
            $stmt->execute(array(
                ":order_id" => $order_id
            ));

            $results = array();
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $dt = \DateTime::createFromFormat('Y-m-d', $row["date_transaction"]);

                $row["date"] = $dt->format('d/m/Y');
                $row["card_number"] = "**** **** **** ".$row["credit_id"];
                
                unset($row["date_transaction"]);
                unset($row["credit_id"]);

                $results[] = $row;
            }

            return $results;
        }

        static public function getAllTransactions($pdo) {
            $stmt = $pdo->query("SELECT client_id, order_id, credit_id, value, date_transaction FROM transaction");
            
            $results = array();
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $dt = \DateTime::createFromFormat('Y-m-d', $row["date_transaction"]);

                $row["date"] = $dt->format('d/m/Y');
                $row["card_number"] = "**** **** **** ".$row["credit_id"];
                
                unset($row["date_transaction"]);
                unset($row["credit_id"]);

                $results[] = $row;
            }

            return $results;
        }

        static public function getTransactionsFromClient($pdo, $client_id) {
            $stmt = $pdo->prepare("SELECT client_id, order_id, credit_id, value, date_transaction FROM transaction WHERE client_id = :client_id");
            $stmt->execute(array(
                ":client_id" => $client_id
            ));

            $results = array();
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $dt = \DateTime::createFromFormat('Y-m-d', $row["date_transaction"]);

                $row["date"] = $dt->format('d/m/Y');
                $row["card_number"] = "**** **** **** ".$row["credit_id"];
                
                unset($row["date_transaction"]);
                unset($row["credit_id"]);

                $results[] = $row;
            }

            return $results;
        }

        /**
         * @link https://www.php.net/manual/en/function.uniqid.php#94959
         * @author Andrew Moore
         */
        static public function generateGUID() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

                // 16 bits for "time_mid"
                mt_rand( 0, 0xffff ),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand( 0, 0x0fff ) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand( 0, 0x3fff ) | 0x8000,

                // 48 bits for "node"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
        }
    }
?>
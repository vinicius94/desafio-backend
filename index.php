<?php
    require "vendor/autoload.php";

    use App\MySQLConnection;
    use App\CreateTables;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;
    use Slim\Routing\RouteCollectorProxy;

    $mySQLConn = new MySQLConnection();
    $pdo = $mySQLConn->connect();

    if ($pdo == null)
        die("Connection Error: ".$mySQLConn->getErrorMessage());    

    $structure = new CreateTables($pdo);
    $structure->create();

    $app = AppFactory::create();
    $app->addErrorMiddleware(true, false, false);

    $app->group("/store/api/v1/", function(RouteCollectorProxy $group) use ($pdo){
        $group->post("product", function ($request, $response, $args) use ($pdo){
            $savedProduct = App\Api\Product::saveProduct($pdo, $request->getParsedBody());

            $response->getBody()->write(json_encode($savedProduct));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->get("products", function ($request, $response, $args) use ($pdo){
            $listProducts = App\Api\Product::getListProducts($pdo);
            
            $response->getBody()->write(json_encode($listProducts));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->post("add_to_cart", function ($request, $response, $args) use ($pdo){
            $savedProduct = App\Api\Cart::add($pdo, $request->getParsedBody());

            $response->getBody()->write(json_encode($savedProduct));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->post("buy", function ($request, $response, $args) use ($pdo){
            $transaction = App\Api\Transaction::saveTransaction($pdo, $request->getParsedBody());

            $response->getBody()->write(json_encode($transaction));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->get("history[/{clientId}]", function ($request, $response, $args) use ($pdo){
            if(!isset($args["clientId"])) {
                $transaction = App\Api\Transaction::getAllTransactions($pdo);

                $response->getBody()->write(json_encode($transaction));
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $transaction = App\Api\Transaction::getTransactionsFromClient($pdo, $args["clientId"]);

                $response->getBody()->write(json_encode($transaction));
                return $response->withHeader('Content-Type', 'application/json');
            }

            return $response;
        });
    });

    $app->run();
?>
<?php
    header("Access-Control-Allow-Origin: *");
    include_once('./config/ClassConection.php');
    require "../vendor/autoload.php";
    use \Firebase\JWT\JWT;

    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $login = '';
    $senha = '';

    $databaseService = new ClassConection();
    $conn = $databaseService -> getConnection();

    $data = json_decode(file_get_contents("php://input"));

    $login = $data -> login;
    $senha = $data -> senha;

    $query = "SELECT * FROM users WHERE login = :login LIMIT 0,1";

    $stmt = $conn->prepare( $query );
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $num = $stmt->rowCount();

    if($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $reg = $row['reg'];
        $nome = $row['nome'];
        $nivel = $row['nivel'];
        $criptSenha = $row['criptSenha'];
        $noCriptSenha = $row['senha'];

        if($criptSenha === "" AND $senha === $noCriptSenha){
            global $login, $senha, $conn;
            $query2 = "UPDATE users  SET criptSenha = :senha WHERE login = '" . $login . "' AND senha = '" . $senha . "'";
            $stmt2 = $conn->prepare($query2);
            $password_hash = password_hash($senha, PASSWORD_BCRYPT);
            $stmt2->bindParam(':senha', $password_hash);
            if(!$stmt2->execute()){
                print_r( $stmt2->errorInfo());
            };

            
        }

        if(password_verify($senha, $criptSenha) OR $senha === $noCriptSenha) {
            $secret_key = "YOUR_SECRET_KEY";
            $issuer_claim = "THE_ISSUER";
            $audience_claim = "THE_AUDIENCE";
            $issuedat_claim = 1356999524; // issued at
            $notbefore_claim = 1357000000; //not before
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "data" => array(
                    "reg" => $reg,
                    "nome" => $nome,
                    "nivel" => $nivel,
                    "login" => $login
            ));
    
            http_response_code(200);
    
            $jwt = JWT::encode($token, $secret_key);
            echo json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "expireAt" => "1 day"
                )
            );

        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Login failed, error password.", "senha" => $senha, "criptSenha" => $criptSenha));
        }
    } else  {
        http_response_code(401);
        echo json_encode(array("message" => "Login failed.", "login" => $login));
    }
?>
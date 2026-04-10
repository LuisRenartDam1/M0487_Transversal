<!-- class UserController
    connection;

    login()
         if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
      
        $_SESSION['user'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password']; 
        $_SESSION['cart'] = [];

        
        header("Location: ../view/shop.php");
        exit;
        // leer datos del form, $_POST
    
        // select en base de datos
    
        // redirect profile
    }

    logout()
        //unset
        //destroy_session
        // redirect home
    

    register()
        // leer datos del form, $_POST

        // insert en base de datos

        // redirect home -->
<?php
echo __LINE__;

// Scanner scaner = new Scanner(system.in);
// scaner.nextLine();

// UserController user = new UserController();
$user = new UserController();
// user.login();

if(isset($_POST["loginButton"])){
    $user->login();
}

if(isset($_POST["registerButton"])){
    $user->register();
}

if(isset($_POST["logoutButton"])){
    $user->logout();
}



class UserController
{
    // Propiedades (atributos)
    public $connection;

    // Método
    public function login()
{

        // leer datos del form, $_POST
        $username = $_POST['username'];
        $password = $_POST['password'];


    // 2. Conectar
    $conexion = new mysqli("localhost", "root", "", "bbddtransversal");

    // 3. LA CONSULTA: Buscamos al usuario que coincida con AMBOS campos
    // Definimos la variable $sql que te faltaba
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    
    $stmt = $conexion->prepare($sql);

    // 4. Vincular parámetros (los dos son strings: "ss")
    $stmt->bind_param("ss", $username, $password);

    // 5. Ejecutar
    $stmt->execute();

    // 6. Obtener resultados
    $resultado = $stmt->get_result();

<<<<<<< HEAD
    // Comprobamos si encontró a alguien
    if ($fila = $resultado->fetch_assoc()) {
        // Si entra aquí, es que el usuario y contraseña son correctos
        session_start();
        $_SESSION['user'] = $fila['username'];
        $_SESSION['cart'] = [];
        
        header("Location: ../view/shop.php");
        exit;
    } else {
        echo "Usuario o contraseña incorrectos.";

    }

    $stmt->close();
    $conexion->close();
}


 function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    
        $_SESSION['user'] = $_POST['username'];
        $_SESSION['cart'] = [];

        
        
        $conexion = mysqli_connect("localhost", "root", "", "BBDDTransversal");

        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        $user = $_POST['username'];
   
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);


        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

       
        mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
        
        if (mysqli_stmt_execute($stmt)) {
            
            $_SESSION['user'] = $user;
            $_SESSION['cart'] = [];
            
            header("Location: ../view/shop.php");
            exit;
        } else {
            
            echo "Error: El usuario ya existe o hubo un problema en la DB.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}


function logout() {
    
    $_SESSION = array();

    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

   
    session_destroy();

    
    header("Location: ../view/login.php");
    exit;
}
}
?>
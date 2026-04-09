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
$user->login();



class UserController
{
    // Propiedades (atributos)
    public $connection;

    // Método
    public function login()
    {
        echo "Hola, soy ";
        // leer datos del form, $_POST
        $username = $_POST['username'];
        $password = $_POST['password'];

        // select en base de datos
        $conexion = new mysqli("localhost", "root", "", "bbddtransversal");//TODO commit .sql model

        // Preparar consulta
        $sql = "SELECT * FROM users WHERE username = ?";//TODO password
        $stmt = $conexion->prepare($sql);

        // Vincular parámetros (tipos: i=integer, s=string, d=double, b=blob)
        $stmt->bind_param("s", $username);

        // Ejecutar
        $stmt->execute();

        // Obtener resultados
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            echo "Nombre: " . $fila['password'] . "<br>";//TODO redirect profile header Pr4Session_shop
        }

        $stmt->close();
        $conexion->close();

        // redirect profile
    }

    public function register()
    {

    }
    public function logout()
    {
    
    }
}
?>
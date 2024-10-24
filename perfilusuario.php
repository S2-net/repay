<?php
require("datos_perfil.php"); 
require_once("conexion.php");

$stmt = $con->prepare("SELECT r.* FROM favoritos f JOIN residencia r ON f.id_residencia = r.id_residencia WHERE f.id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result_favoritos = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="estilo.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<section class="perfil-usuario">
        <div class="contendor-perfil">
            <div class="portada-perfil" style="background-image: url(http://localhost/conexionPHPMysql-main/images/resi4.jpeg);">
                <a href="index.php" class="botonperfil">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <div class="avatar-perfil">
    <?php if (!empty($foto)): ?>
        <img src="<?php echo $foto; ?>" alt="Foto de perfil">
    <?php else: ?>
        <img src="http://localhost/conexionPHPMysql-main/images/user.png" alt="Usuario predeterminado" style="max-width: 150px; max-height: 150px;">
    <?php endif; ?>
    
    <form action="subir_foto.php" method="POST" enctype="multipart/form-data" style="display: inline;">
        <input type="file" name="foto" accept="image/*" required id="input-foto" style="display: none;">
        <button type="button" class="cambiar-foto" id="cambiar-foto">Cambiar Foto</button>
        <button type="submit" style="display: none;" id="submit-foto">Subir Foto</button>
    </form>
</div>

<script>
    document.getElementById('cambiar-foto').addEventListener('click', function() {
        document.getElementById('input-foto').click(); // Abre el explorador de archivos
    });

    document.getElementById('input-foto').addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('submit-foto').click(); // Simula el clic en el botón de envío
        }
    });
</script>
</div>
        </div>
    </section>

    <div class="tony">
        <div class="datosresi1">
            <div class="nombreusu">
                <h1>Nombre de usuario: <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h1>
                <br>
                <p>Correo: <?php echo htmlspecialchars($correo); ?></p>
                <br>
                <p>Contraseña: <button type="button" onclick="abrirModal()">Cambiar Contraseña</button></p>
                <br>
                <p>Género: <?php echo htmlspecialchars($genero); ?></p>
                <br>
                <p>Fecha de Nacimiento: <?php echo htmlspecialchars($fecha_nacimiento); ?></p>
            </div>

            <button class="button" type="button" onclick="borrarCuenta()">
                <span class="button__text">Borrar Cuenta</span>
                <span class="button__icon">
                    <svg class="svg" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
                        <title></title>
                        <path d="M112,112l20,320c.95,18.49,14.4,32,32,32H348c17.67,0,30.87-13.51,32-32l20-320" style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
                        <line style="stroke:#fff;stroke-linecap:round;stroke-miterlimit:10;stroke-width:32px" x1="80" x2="432" y1="112" y2="112"></line>
                        <path d="M192,112V72h0a23.93,23.93,0,0,1,24-24h80a23.93,23.93,0,0,1,24,24h0v40" style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
                        <line style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" x1="256" x2="256" y1="176" y2="400"></line>
                        <line style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" x1="184" x2="192" y1="176" y2="400"></line>
                        <line style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" x1="328" x2="320" y1="176" y2="400"></line>
                    </svg>
                </span>
            </button>
        </div>
        <div class="datosresi3">
    <h1>Residencias Favoritas</h1>
    <?php if ($result_favoritos->num_rows > 0): ?>
        <div class="flex-container">
            <?php while ($row = $result_favoritos->fetch_assoc()): ?>
                <div class="flex-item">
                    <h3>Nombre:<?php echo htmlspecialchars($row['nombreresi']); ?></h3>
                    <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                    <a href="residencia.php?id_residencia=<?php echo $row['id_residencia']; ?>" class="btn btn-primary">Ver Residencia</a>

                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No hay residencias favoritas.</p>
    <?php endif; ?>
</div>
    </div>

  

    <section class="cambiar-contrasena">
        <!-- Modal para cambiar la contraseña -->
        <div id="contrasenaModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="cerrarModal(1)">&times;</span>
                <h1>Cambiar Contraseña</h1>
                <form action="cambiar_contrasena.php" method="POST">
                    <label for="contrasena_actual">Contraseña Actual:</label>
                    <input type="password" id="contrasena_actual" name="contrasena_actual" required>
                    <br>
                    <label for="nueva_contrasena">Nueva Contraseña:</label>
                    <input type="password" id="nueva_contrasena" name="nueva_contrasena" required minlength="8">
                    <br>
                    <label for="confirmar_contrasena">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required minlength="8">
                    <br>
                    <button type="submit" name="cambiar_contrasena">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </section>

    <script src="alerta_cuenta.js"></script>
    <script src="modal_cambiar_contrasena.js"></script>

</body>
</html>

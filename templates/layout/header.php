<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../assets/bootstrap-5.3.6/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <script src="../assets/js/functions.js"></script>
    <title>Mi portal de consultas</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url('/'); ?>">Información</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Aqui van las opciones del menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- ms-auto para alinear a la derecha -->
                <!--ul class="navbar-nav ms-auto"> 
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo base_url('/contacto'); ?>">Contacto</a>
                    </li>
                </ul-->
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>
<section class="text-center" style="margin-bottom: 20%;">
    <h2 class="display-5 fw-bold">Consulta Aquí</h2>

    <div class="row">
        <form id="consultaForm" class="row justify-content-center mt-5">
            <div class="col-6 position-relative">
                <label for="busqueda" class="form-label text-start d-block">Nombre o CURP</label>
                <input type="text" id="busqueda" class="form-control form-autocomplete" placeholder="Nombre completo o CURP" data-type="usuarios" required />
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary w-100" style="margin-top: 30px;">Consultar</button>
            </div>
        </form>
    </div>

    <div id="resultadoConsulta" class="mt-5"></div>
</section>

<section class="text-center" style="margin-bottom: 20%;">
    <h3 class="display-5 fw-bold">Consulta tu trámite aquí</h3>

    <div class="row">
        <form id="consultaForm" class="row justify-content-center mt-5">
            <div class="col-6 position-relative">
                <label for="busqueda" class="form-label text-start d-block">Nombre o CURP</label>
                <input type="text" id="txt-busqueda" name="txt-busqueda" class="form-control form-autocomplete"
                    placeholder="Nombre completo o CURP" data-type="usuarios" data-min-length="3" required />
            </div>
            <div class="col-2">
                <button type="button" id="btnConsultar" class="btn btn-primary w-100" style="margin-top: 30px;">Consultar</button>
            </div>
        </form>
    </div>

    <div id="resultadoConsulta" class="mt-5">

    </div>
</section>
<script>
    const btn = document.getElementById('btnConsultar');
    btn?.addEventListener('click', async () => {
        const texto = document.getElementById('txt-busqueda').value.trim();

        if (!texto) {
            showToast('El campo de búsqueda no puede estar vacío', 'secondary');
            document.getElementById('txt-busqueda').focus();
            return;
        }

        const curp = texto.match(/\(([A-Z0-9]{18})\)/)?.[1];

        btn.disabled = true;
        btn.textContent = 'Consultando...';

        const data = new URLSearchParams({
            op: 'estatus_tramite',
            curp,
            retorno: 'html'
        });

        try {
            const html = await window.envioAjax(data, '/FoxProBridge/public/api/consulta.php', 'html', 'POST');
            document.getElementById('resultadoConsulta').innerHTML = html;
        } catch (err) {
            console.error('Error al consultar:', err);
            showToast('Ocurrió un error al consultar', 'secondary');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Consultar';
        }
    });
</script>
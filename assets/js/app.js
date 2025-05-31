import { initAutocomplete } from './modules/autocomplete.js';
    
// Funcion reutilizable para enviar AJAX por POST y obtener texto, HTML o JSON
window.envioAjax = (data, url, responseType = 'text', method = 'POST') => {
    return new Promise((resolve, reject) => {
        method = method.toUpperCase();

        let fetchUrl = url;
        let fetchOptions = {
            method,
            headers: {},
        };

        const isFormData = data instanceof FormData;

        // Si el metodo es GET, agregar datos como query string
        if (method === 'GET') {
            fetchUrl += '?' + (isFormData ? new URLSearchParams(data).toString() : data.toString());
        } else {
            if (!isFormData) {
                fetchOptions.headers['Content-Type'] = 'application/x-www-form-urlencoded';
            }
            fetchOptions.body = data;
        }

        fetch(fetchUrl, fetchOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la conexión o en la página.');
                }

                switch (responseType) {
                    case 'json': return response.json();
                    case 'html':
                    case 'text':
                    default: return response.text();
                }
            })
            .then(resolve)
            .catch(error => {
                console.error('Error en envioAjax:', error);
                alert('Hubo un problema al conectar con el servidor. Intente más tarde...');
                reject(error);
            });
    });
};

// buscara todos los inputs con la clase .form-autocomplete
document.addEventListener('DOMContentLoaded', () => {
    initAutocomplete(); 
});
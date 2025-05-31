export function initAutocomplete(selector = '.form-autocomplete') {
    document.querySelectorAll(selector).forEach(input => {
        const type = input.dataset.type ?? 'default';
        const dropdown = document.createElement('div');
        dropdown.className = 'autocomplete-dropdown list-group position-absolute shadow-sm';
        dropdown.style.width = 'auto';
        dropdown.style.minWidth = '250px';
        dropdown.style.maxWidth = '650px';
        dropdown.style.zIndex = 1000;
        dropdown.style.display = 'none';
        input.parentNode.appendChild(dropdown);

        input.addEventListener('input', async () => {
            const query = input.value.trim();
            if (query.length < 2) {
                dropdown.style.display = 'none';
                return;
            }

            const endpoint = `${window.location.origin}/FoxProBridge/public/api/autocomplete.php?type=${encodeURIComponent(type)}&q=${encodeURIComponent(query)}`;

            try {
                const res = await fetch(endpoint);
                const data = await res.json();

                dropdown.innerHTML = '';
                if (!Array.isArray(data) || data.length === 0) {
                    dropdown.style.display = 'none';
                    return;
                }

                data.forEach(item => {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'list-group-item list-group-item-action';
                    option.textContent = item.label ?? item;
                    option.addEventListener('click', () => {
                        input.value = item.label ?? item;
                        dropdown.style.display = 'none';
                    });
                    dropdown.appendChild(option);
                });

                dropdown.style.display = 'block';

            } catch (err) {
                console.error('Error en autocomplete:', err);
                dropdown.style.display = 'none';
            }
        });

        // Ocultar el dropdown si se hace clic fuera
        document.addEventListener('click', e => {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });
}
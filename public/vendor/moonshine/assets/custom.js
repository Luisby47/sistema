/* Modal */

function modal_loading() {

    return {
        open: false, // Modal está cerrado inicialmente

        init() {
            // Manejar solo la navegación dentro de enlaces en <li>
            this.handleNavigation();
        },

        handleNavigation() {
            // Interceptar clics en enlaces que están dentro de una lista <li>
            // Interceptar clics en enlaces que están dentro de una lista <li>
            document.querySelectorAll('li a').forEach((link) => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (href && !href.startsWith('#') && !link.hasAttribute('data-no-spinner')) {
                        e.preventDefault();
                        this.open = true; // Mostrar modal
                        setTimeout(() => {
                            window.location.href = href; // Redirigir después de mostrar el modal
                        }, 100); // Pequeño retraso para que el modal aparezca antes de la redirección
                    }
                });
            });

            // Interceptar envíos de formularios para mostrar el modal
            document.querySelectorAll('form').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    const submitter = e.submitter;
                    if (!submitter?.hasAttribute('data-no-spinner')) { // <-- Solo verificamos el botón
                        this.open = true; // Mostrar modal
                    }
                });
            });
        },


        redirectWithModal(url) {
            this.open = true; // Mostrar modal
            setTimeout(() => {
                window.location.href = url; // Redirigir después de un pequeño retraso
            }, 100);
        }
    };
}


document.addEventListener('DOMContentLoaded', function() {
    var btnGenerar = document.getElementById('btn-generar');
    if (btnGenerar) {
        btnGenerar.addEventListener('click', function(e) {
            // Si es un botón de envío, evitar el comportamiento por defecto
            e.preventDefault();
            // Obtén el valor del select
            var select = document.querySelector('select[name="value_type"]');
            if (select && select.value) {
                var valueType = select.value;
                // Construye la URL (asegúrate de que la ruta esté definida en Laravel)
                var url = "{{ url('/generate') }}/" + valueType;
                window.location.href = url;
            } else {
                alert('Por favor, selecciona un tipo de plantilla');
            }
        });
    }
});




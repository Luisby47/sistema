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
                    if (href && !href.startsWith('#') && !link.hasAttribute('data-no-spinner') &&
                        !link.hasAttribute('data-async')) {
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
                    if (!form.hasAttribute('data-async')) {
                        this.open = true;
                    } // Mostrar el modal cuando se envía el formulario
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


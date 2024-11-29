/* Modal */

/*
function modal_loading(isOpen, asyncUrl, autoClose) {
    return {
        open: isOpen === 'true',
        asyncUrl: asyncUrl,
        autoClose: autoClose === 'true',

        dismissModal() {
            if (this.autoClose) {
                this.open = false;
            }
        },
    };
}



function modal_loading() {
    return {
        open: true, // Modal abierto por defecto

        // Lógica para cerrar el modal una vez la página ha cargado
        init() {
            // Cerrar el modal cuando la página esté completamente cargada
            window.addEventListener('DOMContentLoaded', () => {
                this.open = false; // Cerrar el modal
            });
        }
    };
}
 */
function modal_loading() {
    return {
        open: false, // Modal está cerrado inicialmente

        init() {
            // Manejar solo la navegación dentro de enlaces en <li>
            this.handleNavigation();
        },

        handleNavigation() {
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
                    this.open = true; // Mostrar el modal cuando se envía el formulario
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


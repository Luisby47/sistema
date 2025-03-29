/* Toasts */

// Reemplazar la función `p1` para extender el tiempo de los toasts
if (window.Alpine) {
    // Sobrescribir el componente existente
    Alpine.data('toasts', () => ({
        toasts: [],
        visible: [],
        add(e) {
            e.id = Date.now();
            this.toasts.push(e);
            this.fire(e.id);
        },
        fire(e) {
            this.visible.push(this.toasts.find(r => r.id == e));

            // Extender el tiempo a 8 segundos por ejemplo
            const t = 8000 * this.visible.length;

            setTimeout(() => {
                this.remove(e);
            }, t);
        },
        remove(e) {
            const t = this.visible.find(o => o.id == e);
            const r = this.visible.indexOf(t);
            if (r !== -1) {
                this.visible.splice(r, 1);
            }
        },
    }));

    // Reescanea el DOM para inicializar nuevamente el componente `toasts`
    const toastContainer = document.querySelector('[x-data="toasts()"]');
    if (toastContainer) {
        Alpine.initTree(toastContainer);
    }



}

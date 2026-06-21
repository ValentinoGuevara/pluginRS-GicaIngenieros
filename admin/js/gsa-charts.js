document.addEventListener('DOMContentLoaded', function () {

    const azul    = '#2b5ba8';
    const naranja = '#f47920';
    const borde   = '#d0d7e3';

    // ── GRÁFICO 1: CLICS POR DÍA ──
    const ctxDia = document.getElementById('gsaChartDia');
    if (ctxDia && gsaDatos.dias.labels.length > 0) {
        new Chart(ctxDia, {
            type: 'line',
            data: {
                labels: gsaDatos.dias.labels,
                datasets: [{
                    label: 'Clics',
                    data: gsaDatos.dias.data,
                    borderColor: azul,
                    backgroundColor: 'rgba(43,91,168,0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: azul,
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: borde } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ── GRÁFICO 2: CLICS POR RED ──
    const ctxRed = document.getElementById('gsaChartRed');
    if (ctxRed && gsaDatos.redes.labels.length > 0) {
        new Chart(ctxRed, {
            type: 'bar',
            data: {
                labels: gsaDatos.redes.labels,
                datasets: [{
                    label: 'Clics',
                    data: gsaDatos.redes.data,
                    backgroundColor: gsaDatos.redes.colors,
                    borderRadius: 6,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: borde } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ── GRÁFICO 3: DISPOSITIVOS ──
    const ctxDis = document.getElementById('gsaChartDispositivo');
    if (ctxDis && gsaDatos.dispositivos.labels.length > 0) {
        new Chart(ctxDis, {
            type: 'doughnut',
            data: {
                labels: gsaDatos.dispositivos.labels,
                datasets: [{
                    data: gsaDatos.dispositivos.data,
                    backgroundColor: ['#1e7e34', '#c0392b', azul, naranja],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 } } }
                }
            }
        });
    }

    // ── GRÁFICO 4: HORARIOS ──
    const ctxHora = document.getElementById('gsaChartHora');
    if (ctxHora && gsaDatos.horas.labels.length > 0) {
        new Chart(ctxHora, {
            type: 'bar',
            data: {
                labels: gsaDatos.horas.labels,
                datasets: [{
                    label: 'Clics',
                    data: gsaDatos.horas.data,
                    backgroundColor: naranja,
                    borderRadius: 4,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: borde } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
        // ── GRÁFICO 5: NAVEGADORES ──
    const ctxNav = document.getElementById('gsaChartNavegador');
    if (ctxNav && gsaDatos.navegadores.labels.length > 0) {
        new Chart(ctxNav, {
            type: 'doughnut',
            data: {
                labels: gsaDatos.navegadores.labels,
                datasets: [{
                    data: gsaDatos.navegadores.data,
                    backgroundColor: ['#2b5ba8', '#f47920', '#1e7e34', '#c0392b', '#9146FF'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 } } }
                }
            }
        });
    }

});
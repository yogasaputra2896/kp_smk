document.addEventListener("DOMContentLoaded", function () {

    /* =========================
       Chart Booking Job (Bar)
    ==========================*/
    const ctxBooking = document.getElementById("chartBookingJob");
    if (ctxBooking) {
        new Chart(ctxBooking, {
            type: "bar",
            data: {
                labels: window.chartBookingLabels || [],
                datasets: [{
                label: 'Total Booking Job',
                data: window.chartBookingData,
                backgroundColor: '#435ebe',
                borderColor: '#435ebe',
                borderWidth: 1
            }]
            }
        });
    }

    /* =========================
       Chart Worksheet (Pie)
    ==========================*/
    const ctxWorksheet = document.getElementById("chartWorksheet");
    if (ctxWorksheet) {
        new Chart(ctxWorksheet, {
            type: "pie",
            data: {
                labels: ["Import", "Export"],
                datasets: [{
                label: 'Worksheet',
                data: window.chartWorksheetData,
                backgroundColor: ['#ffc107', '#198754'],
                borderColor: ['#ffc107', '#198754'],
                borderWidth: 1
            }]
            }
        });
    }

});

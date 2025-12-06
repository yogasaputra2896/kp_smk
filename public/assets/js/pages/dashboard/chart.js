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

    /* ======================================
    NEW: Chart Booking Job Per Hari (Line)
    =======================================*/
    const ctxBookingPerDay = document.getElementById("chartBookingPerDay");
    if (ctxBookingPerDay) {

        // Data dari PHP
        const rawLabels = window.bookingPerDayLabels || [];
        const rawData   = window.bookingPerDayTotals || [];

        // Ambil bulan dan tahun sekarang
        const today = new Date();
        const year  = today.getFullYear();
        const month = today.getMonth(); // 0 = Jan

        // Nama bulan
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        // Jumlah hari dalam bulan ini
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let completeLabels = [];
        let completeData   = [];

        for (let day = 1; day <= daysInMonth; day++) {
            const dateObj = new Date(year, month, day);
            const dateStr = dateObj.toISOString().slice(0, 10); // format: YYYY-MM-DD

            // Label hanya tanggal -> "01", "02", dst
            const dayLabel = String(day).padStart(2, '0');

            completeLabels.push(dayLabel);

            // Dataset: cocokkan tanggal
            const index = rawLabels.indexOf(dateStr);
            completeData.push(index !== -1 ? rawData[index] : 0);
        }

        new Chart(ctxBookingPerDay, {
            type: "line",
            data: {
                labels: completeLabels,
                datasets: [{
                    label: `Booking Job Per Hari (${monthNames[month]})`,
                    data: completeData,
                    borderColor: "#0d6efd",
                    backgroundColor: "rgba(13, 110, 253, 0.2)",
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }



});

$(document).ready(function() {
    $('#tblLog').DataTable({
        scrollCollapse: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        order: [[3, "desc"]]
    });
});
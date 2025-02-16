import { DataTable } from "simple-datatables";
document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("datatables")) {
        const dataTable = new DataTable("#datatables", {
            searchable: true,
            perPage: 10,
            // perPageSelect: [5, 10, 20, 50],
        });
    }
});

window.addEventListener('DOMContentLoaded', event => {

    const transactionTable = document.getElementById('transactionTable');
    if (transactionTable) {
        new simpleDatatables.DataTable(transactionTable);
    }
});

//------------------------------------------------------------
var current_page = 1;
var records_per_page = 10;
var l = document.getElementById("table_id").rows.length;
//------------------------------------------------------------

function toggleMenu() {
    console.log('Toggle menu clicked'); // เพิ่มบรรทัดนี้เพื่อตรวจสอบการทำงาน
    const sidebar = document.getElementById("sidebar");
    if (sidebar.style.display === "block") {
        sidebar.style.display = "none"; // ซ่อน
    } else {
        sidebar.style.display = "block"; // แสดง
    }
}

const hamburger = document.querySelector('.hamburger');
const leftContent = document.querySelector('.left-content');

hamburger.addEventListener('click', () => {
    leftContent.classList.toggle('active');
});

//------------------------------------------------------------
function apply_Number_of_Rows() {
    var x = document.getElementById("number_of_rows").value;
    records_per_page = x;
    changePage(current_page);
}
//------------------------------------------------------------

//------------------------------------------------------------
function prevPage() {
    if (current_page > 1) {
        current_page--;
        changePage(current_page);
    }
}
//------------------------------------------------------------

//------------------------------------------------------------
function nextPage() {
    if (current_page < numPages()) {
        current_page++;
        changePage(current_page);
    }
}
//------------------------------------------------------------

//------------------------------------------------------------
function changePage(page) {
    var btn_next = document.getElementById("btn_next");
    var btn_prev = document.getElementById("btn_prev");
    var listing_table = document.getElementById("table_id");
    var page_span = document.getElementById("page");
    // Validate page
    if (page < 1) page = 1;
    if (page > numPages()) page = numPages();
    // Hide all table rows
    for (var i = 1; i < listing_table.rows.length; i++) {
        listing_table.rows[i].style.display = "none";
    }
    // Display the correct rows
    for (var i = (page - 1) * records_per_page + 1; i < (page * records_per_page) + 1; i++) {
        if (listing_table.rows[i]) {
            listing_table.rows[i].style.display = "";
        }
    }
    page_span.innerHTML = page + "/" + numPages();
    // Enable or disable buttons
    btn_prev.disabled = page == 1;
    btn_next.disabled = page == numPages();
}
//------------------------------------------------------------

//------------------------------------------------------------
function numPages() {
    return Math.ceil((l - 1) / records_per_page);
}
//------------------------------------------------------------


// Initial page load
changePage(1);

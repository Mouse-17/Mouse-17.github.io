const optionYard = document.querySelectorAll(".option-label"); 
const typeYard = document.getElementById("type-yard");

const timeFrame = document.querySelectorAll(".time-frame");
const time = document.getElementById("time");

optionYard.forEach(element => {
    element.addEventListener("click", (event) => {
        let currentYard = event.currentTarget.textContent;
        typeYard.innerHTML = currentYard;
    });
});

timeFrame.forEach(ele => {
    ele.addEventListener("click", (event) => {
        let choosetime = event.currentTarget.textContent;
        time.innerHTML = choosetime;
    });
});

// tìm kiếm
const clickSearch = document.getElementById("header__search-btn");
const showSearch = document.getElementById("header__search-form");

// Khi click vào icon tìm kiếm, hiển thị form
clickSearch.addEventListener("click", function(event) {
    event.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
    showSearch.style.display = "block";
});

// Khi click ra ngoài, ẩn form
document.addEventListener("click", function(event) {
    if (!showSearch.contains(event.target) && !clickSearch.contains(event.target)) {
        showSearch.style.display = "none";
    }
});



// product detail
const changeTab = document.querySelectorAll(".detail-infor__desc-rating");
const descBox = document.getElementById("desc-box");
const commentBox = document.getElementById("comment-box");
if (changeTab.length > 0) {
    changeTab[0].classList.add("desc-detail__seleted");
    descBox.style.display = "block";
    commentBox.style.display = "none";
}

changeTab.forEach((element, index) => {
    element.addEventListener("click", (event) => {
        changeTab.forEach(tab => {
            tab.classList.remove("desc-detail__seleted");
        });
        event.currentTarget.classList.add("desc-detail__seleted");

        if(index === 0) {
            descBox.style.display = "block";
            commentBox.style.display = "none";
        }else{
            descBox.style.display = "none";
            commentBox.style.display = "block";
        }
    });
});


// chọn voucher
const showVoucher = document.getElementById("showVoucher");
const voucherBox = document.getElementById("voucherBoxId");

showVoucher.addEventListener("click", function () {
    voucherBox.style.display = "block";
    document.body.classList.add("background-after");
    document.body.style.overflow = "hidden"; // Vô hiệu hóa cuộn

    // Cuộn đến vị trí của voucherBox
    voucherBox.scrollIntoView({ behavior: "smooth", block: "end" });
});

document.addEventListener("click", function (e) {
    if (!voucherBox.contains(e.target) && !showVoucher.contains(e.target)) {
        voucherBox.style.display = "none";
        document.body.classList.remove("background-after");
        document.body.style.overflow = ""; // Bật lại cuộn
    }
});

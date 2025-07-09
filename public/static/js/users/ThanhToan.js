// Lấy dữ liệu thanh toán
let paymentData = {};

// Khởi tạo khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PAYMENT PAGE LOADED ===');
    
    // Force stop loading
    stopAllLoading();
    
    // Delay để đảm bảo
    setTimeout(stopAllLoading, 100);
    setTimeout(stopAllLoading, 500);
    
    // ✅ Clear loading state NGAY LẬP TỨC
    document.title = "Thanh Toán Vé";
    
    // Stop any spinning animations
    document.documentElement.style.cursor = 'default';
    document.body.style.cursor = 'default';
    
    // Remove loading classes
    document.body.classList.remove('loading');
    document.documentElement.classList.remove('loading');
    
    // Hide all loading elements
    const loadingElements = document.querySelectorAll('.loading, .spinner, .loading-spinner, .fa-spinner');
    loadingElements.forEach(el => {
        el.style.display = 'none';
        el.remove();
    });
    
    // ✅ Lấy dữ liệu thanh toán từ script tag
    try {
        const paymentDataElement = document.getElementById('payment-data');
        if (paymentDataElement) {
            paymentData = JSON.parse(paymentDataElement.textContent);
            console.log('Payment data loaded:', paymentData);
        }
    } catch (e) {
        console.error('Error loading payment data:', e);
    }
    
    console.log('=== PAYMENT INITIALIZATION COMPLETE ===');
});

// ✅ Thêm function để force stop loading
function stopAllLoading() {
    // Clear title
    document.title = "Thanh Toán Vé";
    
    // Clear any intervals
    const highestIntervalId = setInterval(() => {}, 9999);
    for (let i = 0; i < highestIntervalId; i++) {
        clearInterval(i);
    }
    
    // Clear any timeouts
    const highestTimeoutId = setTimeout(() => {}, 9999);
    for (let i = 0; i < highestTimeoutId; i++) {
        clearTimeout(i);
    }
    
    // Stop loading cursor
    document.documentElement.style.cursor = 'default';
    document.body.style.cursor = 'default';
    
    // Hide loading elements
    const loadingElements = document.querySelectorAll('.loading, .spinner, .loading-spinner, .fa-spinner, .fa-spin');
    loadingElements.forEach(el => {
        el.style.display = 'none';
        el.classList.remove('fa-spin');
        el.remove();
    });
    
    console.log('All loading stopped');
}

// Xử lý thanh toán
function processPayment() {
    console.log('=== PROCESS PAYMENT STARTED ===');
    
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    
    if (!paymentMethod) {
        alert('Vui lòng chọn phương thức thanh toán!');
        return;
    }
    
    // Disable nút thanh toán
    const paymentBtn = document.querySelector('[onclick="processPayment()"]');
    if (paymentBtn) {
        paymentBtn.disabled = true;
        paymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    }
    
    // Gửi request thanh toán
    const bookingData = {
        lichChieuId: paymentData.lichChieuId,
        seatDetails: paymentData.seatDetails,
        total: paymentData.total,
        paymentMethod: paymentMethod.value
    };
    
    console.log('Sending booking data:', bookingData);
    
    fetch('/xu-ly-thanh-toan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(bookingData)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Payment response:', data);
        
        if (data.success) {
            alert('Thanh toán thành công! Mã thanh toán: ' + data.payment_code);
            // Chuyển về trang chủ hoặc vé của tôi
            window.location.href = '/ve-cua-toi';
        } else {
            alert('Lỗi thanh toán: ' + data.message);
        }
        
        // Enable lại nút
        if (paymentBtn) {
            paymentBtn.disabled = false;
            paymentBtn.innerHTML = '<i class="fas fa-credit-card"></i> Thanh toán';
        }
    })
    .catch(error => {
        console.error('Payment error:', error);
        alert('Có lỗi xảy ra khi xử lý thanh toán!');
        
        // Enable lại nút
        if (paymentBtn) {
            paymentBtn.disabled = false;
            paymentBtn.innerHTML = '<i class="fas fa-credit-card"></i> Thanh toán';
        }
    });
}

// Navigation functions
function goBack() {
    window.history.back();
}

function goToMyTickets() {
    window.location.href = '/ve-cua-toi';
}

function goToHome() {
    window.location.href = '/';
}

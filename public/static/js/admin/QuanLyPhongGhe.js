
$(document).ready(function() {
    let selectedSeats = [];
    
    // ✅ XỬ LÝ CHỌN GHẾ CHO CÁC CHỨC NĂNG KHÁC (GIỮ NGUYÊN)
    $('.seat-demo').click(function() {
        const seatCode = $(this).data('seat');
        
        // Bỏ qua ghế chú thích
        if ($(this).hasClass('seat-legend')) {
            return false;
        }
        
        // Bỏ qua ghế đã bán/booked
        if ($(this).hasClass('seat-booked')) {
            return false;
        }
        
        if (!seatCode) {
            return false;
        }
        
        if ($(this).hasClass('seat-selected')) {
            // Bỏ chọn ghế
            $(this).removeClass('seat-selected');
            selectedSeats = selectedSeats.filter(s => s !== seatCode);
        } else {
            // Chọn ghế
            $(this).addClass('seat-selected');
            selectedSeats.push(seatCode);
        }
        
        updateSelectionDisplay();
    });
    
    function updateSelectionDisplay() {
        $('.seat-selection-count').remove();
        
        // if (selectedSeats.length > 0) {
        //     const counter = $(`
        //         <div class="seat-selection-count" style="position: fixed; bottom: 20px; right: 20px; background: #198754; color: white; padding: 10px; border-radius: 5px; z-index: 1000;">
        //             <i class="bi bi-check-circle"></i> 
        //             Đã chọn: ${selectedSeats.length} ghế
        //             <br><small>${selectedSeats.join(', ')}</small>
        //         </div>
        //     `);
        //     $('body').append(counter);
        // }
    }
    
    // ✅ XỬ LÝ CÁC NÚT CHỨC NĂNG KHÁC (GIỮ NGUYÊN)
    $('#btn-vip').click(() => updateSelectedSeatsType('vip'));
    $('#btn-normal').click(() => updateSelectedSeatsType('normal'));
    $('#btn-luxury').click(() => updateSelectedSeatsType('luxury'));
    $('#btn-lock').click(() => updateSelectedSeatsStatus('locked'));
    $('#btn-unlock').click(() => updateSelectedSeatsStatus('available'));
    
    // ================================================
    // ✅ PHẦN MỚI: XỬ LÝ MODAL QUẢN LÝ GIÁ TOÀN CỤC
    // ================================================
    
    // Mở modal và tải dữ liệu
    $('#btn-global-price-manager').click(function() {
        loadGlobalPriceStats();
        $('#updatePriceForm').hide();
    });
    
    function loadGlobalPriceStats() {
        // Hiển thị loading
        $('#currentPricesTable tr').each(function() {
            $(this).find('[id^="price-"]').text('Đang tải...');
            $(this).find('[id^="count-"]').text('...');
        });
        
        $.ajax({
            url: '/get-global-price-stats',
            method: 'GET',
            dataType: 'json'
        })
        .done(function(response) {
            console.log('📊 Price stats response:', response);
            
            if (response.success) {
                updateGlobalPriceStatsTable(response.data);
            } else {
                alert('Lỗi tải dữ liệu: ' + (response.message || 'Unknown error'));
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Failed to load global price stats:', status, error);
            alert('Không thể tải dữ liệu thống kê giá ghế!');
        });
    }
    
    function updateGlobalPriceStatsTable(stats) {
        console.log('📈 Updating table with stats:', stats);
        
        const seatTypes = ['normal', 'vip', 'luxury', 'couple'];
        
        seatTypes.forEach(type => {
            const stat = stats.find(s => s.g_loaighe === type);
            
            if (stat) {
                const avgPrice = parseInt(stat.avg_price || 0);
                const totalSeats = parseInt(stat.total_seats || 0);
                
                $(`#price-${type}`).text(`+${avgPrice.toLocaleString()}đ`);
                $(`#count-${type}`).text(`${totalSeats} ghế`);
                
                console.log(`📋 ${type}: ${avgPrice}đ, ${totalSeats} ghế`);
            } else {
                $(`#price-${type}`).text('0đ');
                $(`#count-${type}`).text('0 ghế');
                
                console.log(`📋 ${type}: không có dữ liệu`);
            }
        });
    }
    
    // Xử lý nút "Sửa" cho từng loại ghế
    $('.edit-price-btn').click(function() {
        const seatType = $(this).data('type');
        const currentPriceText = $(`#price-${seatType}`).text();
        const currentPrice = currentPriceText.replace(/[^0-9]/g, '') || '0';
        
        console.log(`✏️ Editing price for ${seatType}, current: ${currentPrice}`);
        
        // Cập nhật UI
        $('#selectedSeatType')
            .text(seatType.toUpperCase())
            .attr('class', `badge bg-${getSeatTypeColor(seatType)}`);
        
        $('#newSeatPrice').val(currentPrice);
        $('#updatePriceForm').show();
        
        updateGlobalPreview();
    });
    
    function getSeatTypeColor(type) {
        const colors = {
            'normal': 'secondary',
            'vip': 'warning',
            'luxury': 'info', 
            'couple': 'danger'
        };
        return colors[type] || 'secondary';
    }
    
    // Xử lý preset giá nhanh
    $('.global-price-preset').click(function() {
        const price = $(this).data('price');
        $('#newSeatPrice').val(price);
        updateGlobalPreview();
        
        console.log(`🎯 Applied preset: ${price}`);
    });
    
    // Cập nhật preview khi nhập giá
    $('#newSeatPrice').on('input', updateGlobalPreview);
    
    function updateGlobalPreview() {
        const seatType = $('#selectedSeatType').text().toLowerCase();
        const newPrice = $('#newSeatPrice').val();
        const countText = $(`#count-${seatType}`).text();
        const seatCount = countText.replace(/[^0-9]/g, '') || '0';
        
        if (newPrice !== '' && !isNaN(newPrice) && parseInt(newPrice) >= 0) {
            $('#affectedCount').text(seatCount);
            $('#affectedType').text(seatType.toUpperCase());
            $('#previewPrice').text(parseInt(newPrice).toLocaleString());
            $('#pricePreview').show();
            $('#confirmGlobalUpdate').prop('disabled', false);
            
            console.log(`👁️ Preview: ${seatCount} ${seatType} seats → ${newPrice}đ`);
        } else {
            $('#pricePreview').hide();
            $('#confirmGlobalUpdate').prop('disabled', true);
        }
    }
    
    // Hủy sửa giá
    $('#cancelPriceEdit').click(function() {
        $('#updatePriceForm').hide();
        $('#newSeatPrice').val('');
        $('#pricePreview').hide();
        $('#confirmGlobalUpdate').prop('disabled', true);
        
        console.log('❌ Cancelled price edit');
    });
    
    // Xác nhận cập nhật giá toàn cục
    $('#confirmGlobalUpdate').click(function() {
        const seatType = $('#selectedSeatType').text().toLowerCase();
        const newPrice = $('#newSeatPrice').val();
        const affectedCount = $('#affectedCount').text();
        
        if (!seatType || !newPrice || isNaN(newPrice) || parseInt(newPrice) < 0) {
            alert('❌ Vui lòng nhập giá hợp lệ!');
            return;
        }
        
        const confirmMessage = `🚨 BẠN CÓ CHẮC CHẮN?\n\n` +
            `✅ Cập nhật giá: ${parseInt(newPrice).toLocaleString()}đ\n` +
            `✅ Cho: ${affectedCount} ghế loại ${seatType.toUpperCase()}\n` +
            `✅ Phạm vi: TẤT CẢ PHÒNG CHIẾU\n\n` +
            `⚠️ Hành động này KHÔNG THỂ HOÀN TÁC!`;
        
        if (confirm(confirmMessage)) {
            updateGlobalSeatPrice(seatType, parseInt(newPrice));
        }
    });
    
    function updateGlobalSeatPrice(seatType, price) {
        console.log(`🔄 Updating global price: ${seatType} → ${price}đ`);
        
        // Hiển thị loading overlay
        $('body').append(`
            <div class="global-loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); color: white; display: flex; align-items: center; justify-content: center; z-index: 10000; flex-direction: column;">
                <div class="spinner-border text-warning mb-3" role="status"></div>
                <h5>🔄 Đang cập nhật giá toàn hệ thống...</h5>
                <p>Vui lòng chờ, đang xử lý ${$('#affectedCount').text()} ghế loại ${seatType.toUpperCase()}</p>
            </div>
        `);
        
        $.ajax({
            url: '/update-global-seat-prices',
            method: 'POST',
            data: {
                loai_ghe: seatType,
                gia_ghe: price
                // ✅ KHÔNG TRUYỀN phong_chieu = CẬP NHẬT TẤT CẢ
            },
            dataType: 'json'
        })
        .done(function(response) {
            console.log('✅ Global price update response:', response);
            $('.global-loading-overlay').remove();
            
            if (response && response.success) {
                const message = `✅ THÀNH CÔNG!\n\n` +
                    `🎯 Đã cập nhật: ${response.affected_rows || 'tất cả'} ghế\n` +
                    `💺 Loại ghế: ${seatType.toUpperCase()}\n` +
                    `💰 Giá mới: +${price.toLocaleString()}đ\n` +
                    `🌐 Phạm vi: Toàn hệ thống`;
                
                alert(message);
                
                // Đóng modal và reload trang
                $('#globalPriceModal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('❌ Lỗi: ' + (response ? response.message : 'Unknown error'));
            }
        })
        .fail(function(xhr, status, error) {
            console.error('💥 AJAX error for global price update:', status, error);
            $('.global-loading-overlay').remove();
            
            let errorMessage = '❌ Có lỗi xảy ra khi cập nhật giá ghế!\n\n';
            if (xhr.responseText) {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMessage += 'Chi tiết: ' + (errorResponse.message || 'Unknown error');
                } catch (e) {
                    errorMessage += 'Status: ' + status;
                }
            }
            
            alert(errorMessage);
        });
    }
    
    // ================================================
    // ✅ CÁC FUNCTION KHÁC (GIỮ NGUYÊN)
    // ================================================
    
    function updateSelectedSeatsType(type) {
        if (selectedSeats.length === 0) {
            alert('Vui lòng chọn ghế trước!');
            return;
        }
        
        let completed = 0;
        let failed = 0;
        
        selectedSeats.forEach(seat => {
            $.ajax({
                url: '/update-seat-type',
                method: 'POST',
                data: {
                    ma_ghe: seat,
                    loai_ghe: type
                },
                dataType: 'json'
            })
            .done(function(response) {
                if (response && response.success) {
                    completed++;
                    $(`.seat-demo[data-seat="${seat}"]`)
                        .removeClass('seat-normal seat-vip seat-luxury seat-locked seat-selected')
                        .addClass('seat-' + type);
                } else {
                    failed++;
                }
                
                if (completed + failed === selectedSeats.length) {
                    if (failed > 0) {
                        alert(`Cập nhật hoàn tất. ${completed} ghế thành công, ${failed} ghế thất bại.`);
                    } else {
                        alert(`Cập nhật thành công ${completed} ghế!`);
                    }
                    
                    selectedSeats = [];
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            })
            .fail(function(xhr, status, error) {
                failed++;
                if (completed + failed === selectedSeats.length) {
                    alert(`Có lỗi xảy ra! ${completed} ghế thành công, ${failed} ghế thất bại.`);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        });
    }
    
    $('.seat-action-btn').click(function() {
        const action = $(this).data('action');
        const seatCode = $(this).data('seat');
        const newStatus = action === 'lock' ? 'locked' : 'available';
        
        if (confirm(`Bạn có chắc muốn ${action === 'lock' ? 'khóa' : 'mở khóa'} ghế ${seatCode}?`)) {
            updateSeatStatus(seatCode, newStatus);
        }
    });
    
    function updateSeatStatus(seatCode, status) {
        $.ajax({
            url: '/update-seat-status',
            method: 'POST',
            data: {
                ma_ghe: seatCode,
                trang_thai: status
            },
            dataType: 'json'
        })
        .done(function(response) {
            if (response && response.success) {
                alert('Cập nhật thành công!');
                location.reload();
            } else {
                alert('Lỗi: ' + (response ? response.message : 'Unknown error'));
            }
        })
        .fail(function(xhr, status, error) {
            alert('Có lỗi xảy ra khi cập nhật trạng thái ghế!');
        });
    }
    
    function updateSelectedSeatsStatus(status) {
        if (selectedSeats.length === 0) {
            alert('Vui lòng chọn ghế trước!');
            return;
        }
        
        selectedSeats.forEach(seat => {
            updateSeatStatus(seat, status);
        });
    }
});

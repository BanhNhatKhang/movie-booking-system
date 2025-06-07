<?php
    $activePage='member';
    include __DIR__ . '/../../../layouts/users/Header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điểm thưởng</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/users/ThanhVien.css">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
</head>
<body style="background-color: rgb(40, 40, 40)">
    <main >
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row bg-body m-0">
                        <div class="col-lg-3">
                            <ul class="py-3 px-2 m-0">
                                <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm member-hover" >
                                    <li class="list-unstyled "><a href="ThanhVienKHF.php" class="text-decoration-none text-dark fw-bold">THÀNH VIÊN KHF CINEMA</a></li>
                                </div>
                                <div class=" p-2 mb-3 rounded-start-pill shadow-sm " style="background-color: #ff4444;">
                                    <li class="list-unstyled"><a href="#" class="text-decoration-none text-white fw-bold">ĐIỂM THƯỞNG TÍCH LŨY</a></li>
                                </div>
                                <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm  member-hover">
                                    <li class="list-unstyled"><a href="CapDo.php" class="text-decoration-none text-dark fw-bold">CẤP ĐỘ THÀNH VIÊN</a></li>                
                                </div>
                                <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm  member-hover">
                                    <li class="list-unstyled"><a href="QuaTang.php" class="text-decoration-none text-dark fw-bold">QUÀ TẶNG SINH NHẬT</a></li>
                                </div>
                            </ul>
                        </div>
                        <div class="col-lg-9">
                            <div class="p-3">
                                <h2>Chương trình Tích điểm KHF Cinema Membership</h2><hr>
                                <h4>1. Cách thức tích lũy điểm thưởng</h4>
                                <p>Mỗi khi bạn thực hiện giao dịch tại quầy vé hoặc quầy thực phẩm tại KHF Cinema, điểm sẽ được tự động cộng vào tài khoản thành viên.</p>

                                <table class="boder boder-secondary table table-striped table-bordered">
                                    <tr>
                                    <th>Giao dịch</th>
                                    <th>Member</th>
                                    <th>VIP</th>
                                    <th>VVIP</th>
                                    </tr>
                                    <tr>
                                    <td>Quầy vé</td>
                                    <td>5%</td>
                                    <td>7%</td>
                                    <td>10%</td>
                                    </tr>
                                    <tr>
                                    <td>Quầy thực phẩm</td>
                                    <td>2%</td>
                                    <td>3%</td>
                                    <td>5%</td>
                                    </tr>
                                </table>
                                <br>
                                <h4>2. Cách quy đổi điểm thưởng</h4>
                                <p>Điểm có thể quy đổi thành voucher điện tử với giá trị tương đương tiền mặt.</p>
                                <ul>
                                    <li><strong>1 điểm = 1.000 VNĐ</strong></li>
                                    <li>Dùng để mua vé, combo, thức uống tại các rạp KHF Cinema.</li>
                                </ul>
                                <div class="note">
                                    <h4>3. Lưu ý quan trọng</h4>
                                    <div>
                                        <ul>
                                        <li>Điểm sẽ được cộng vào ngày kế tiếp sau giao dịch.</li>
                                        <li>Điểm thưởng có hạn dùng 12 tháng kể từ ngày phát sinh.</li>
                                        <li>Điểm xét hạng sẽ đặt lại về 0 vào ngày 31/12 mỗi năm.</li>
                                        <li>Không tích điểm cho các giao dịch sử dụng điểm thưởng hoặc voucher.</li>
                                        <li>Tài khoản không hoạt động 12 tháng sẽ bị trừ toàn bộ điểm.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <?php
        include __DIR__ . '/../../../layouts/users/Footer.php';
    ?>
</body>
</html>
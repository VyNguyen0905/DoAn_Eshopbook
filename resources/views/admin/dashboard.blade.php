@extends('admin_layout')

@section('admin_content')
<h3>Chào mừng bạn đến với trang thống kê đơn hàng</h3>

<!-- Bảng thống kê đơn hàng -->
<table border="1" style="width: 60%; margin-top: 50px; border-collapse: collapse; text-align: center;">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Giá trị</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Số đơn hàng đã bán</td>
            <td>10 đơn</td>
        </tr>
        <tr>
            <td>Tổng số đơn hàng</td>
            <td>100 đơn</td>
        </tr>
        <tr>
            <td>Tỷ lệ đã bán</td>
            <td>10%</td>
        </tr>
    </tbody>
</table>

@endsection

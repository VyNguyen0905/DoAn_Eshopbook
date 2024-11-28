@extends('layout')
@section('content')
<style>
    .table {
        width: 100%;
        /* Đảm bảo bảng chiếm toàn bộ chiều rộng */
        border-collapse: collapse;
        /* Hợp nhất các viền bảng */
    }

    .table th,
    .table td {
        padding: 12px 15px;
        text-align: left;
        border: 1px solid #ddd;
        font-weight: bold;
    }

    .text-alert {
        margin-bottom: 15px;
        font-weight: bold;
    }
</style>
<div class="table-agile-info">
    <div class="panel panel-default">
        <div class="panel-heading">
            Thông tin ghi chú vận chuyển của đơn hàng
        </div>
        <div class="table-responsive">
            <?php
            $message = Session::get('message');
            if ($message) {
                echo '<span class="text-alert">' . $message . '</span>';
                Session::put('message', null);
            }
            ?>
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th>Tên người nhận hàng</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Ghi chú</th>
                        <th>Hình thức thanh toán</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$shipping->shipping_name}}</td>
                        <td>{{$shipping->shipping_address}}</td>
                        <td>{{$shipping->shipping_phone}}</td>
                        <td>{{$shipping->shipping_email}}</td>
                        <td>{{$shipping->shipping_notes}}</td>
                        <td>@if($shipping->shipping_method==0) Chuyển khoản @else Tiền mặt @endif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br><br>

<div class="table-agile-info">
    <div class="panel panel-default">
        <div class="panel-heading" id="vanchuyen">
            Đơn hàng đã đặt
        </div>
        <div class="table-responsive">
            <?php
            $message = Session::get('message');
            if ($message) {
                echo '<span class="text-alert">' . $message . '</span>';
                Session::put('message', null);
            }
            ?>
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng đặt</th>
                        <th>Giá sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 0;
                    $total = 0;
                    @endphp
                    @foreach($order_details as $key => $details)
                    @php
                    $i++;
                    $subtotal = $details->product_price*$details->product_sales_quantity;
                    $total+=$subtotal;
                    @endphp
                    <tr class="color_qty_{{$details->product_id}}">
                        <td>{{$details->product_name}}</td>
                        <td>
                            {{$details->product_sales_quantity}}
                        </td>
                        <td>{{number_format($details->product_price ,0,',','.')}}đ</td>
                        <td>{{number_format($subtotal ,0,',','.')}}đ</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">
                            Thanh toán: {{ number_format($total, 0, ',', '.') }}đ
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- <a target="_blank" href="{{url('/print-order/'.$details->order_code)}}">In đơn hàng</a> -->
        </div>
    </div>
</div>
<script type="text/javascript">
    window.onload = function() {
        // Cuộn đến phần tử có id "cart-center" khi trang giỏ hàng tải
        var element = document.getElementById("vanchuyen");
        if (element) {
            element.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }
</script>
@endsection
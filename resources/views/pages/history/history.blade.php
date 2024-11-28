@extends('layout')
@section('content')
<div class="table-agile-info">
    <div class="panel panel-default">
        <div class="panel-heading" id="history-center">
            Đây là lịch sử tất cả đơn hàng
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
                        <th>Thứ tự</th>
                        <th>Mã đơn hàng</th>
                        <th>Ngày tháng đặt hàng</th>
                        <th>Tình trạng đơn hàng</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 0;
                    @endphp
                    @foreach($order as $key => $ord)
                    @php
                    $i++;
                    @endphp
                    <tr>
                        <td><i>{{$i}}</i></label></td>
                        <td>{{ $ord->order_code}}</td>
                        <td>{{ $ord->created_at}}</td>
                        <td>@if($ord->order_status==1)
                            Đơn hàng mới
                            @else
                            Đã xử lý
                            @endif
                        </td>
                        <td>
                            <a href="{{ URL::to('/view-history-order/'.$ord->order_code) }}" class="active styling-edit btn btn-success">
                                Xem chi tiết đơn hàng
                            </a>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.onload = function() {
        // Cuộn đến phần tử có id "cart-center" khi trang giỏ hàng tải
        var element = document.getElementById("history-center");
        if (element) {
            element.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }
</script>
@endsection
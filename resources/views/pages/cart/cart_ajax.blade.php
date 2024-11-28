@extends('layout')
@section('content')

<section id="cart_items">
    <style>
        #cart_items .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 15px;
            box-sizing: border-box;
        }
    </style>
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
                <li class="active">Giỏ hàng của bạn</li>
            </ol>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {!! session()->get('message') !!}
        </div>
        @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {!! session()->get('error') !!}
        </div>
        @endif
        <div class="table-responsive cart_info">
            <form action="{{url('/update-cart')}}" method="POST">
                @csrf
                <table class="table table-condensed">
                    <thead>
                        <tr class="cart_menu">
                            <td id="cart-center" class="image">Hình ảnh</td>
                            <td class="description">Tên sản phẩm</td>
                            <td class="price">Giá sản phẩm</td>
                            <td class="quantity">Số lượng</td>
                            <td class="total">Thành tiền</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(Session::get('cart')==true)
                        @php
                        $total = 0;
                        @endphp
                        @foreach(Session::get('cart') as $key => $cart)
                        @php
                        $subtotal = $cart['product_price']*$cart['product_qty'];
                        $total+=$subtotal;
                        @endphp
                        <tr>
                            <td class="cart_product">
                                <img src="{{asset('public/uploads/product/'.$cart['product_image'])}}" width="90" alt="{{$cart['product_name']}}" />
                            </td>
                            <td class="cart_description">
                                <h4><a href=""></a></h4>
                                <p>{{$cart['product_name']}}</p>
                            </td>
                            <td class="cart_price">
                                <p>{{number_format($cart['product_price'],0,',','.')}}đ</p>
                            </td>
                            <td class="cart_quantity">
                                <div class="cart_quantity_button">
                                    <input class="cart_quantity" type="number" min="1" name="cart_qty[{{$cart['session_id']}}]" value="{{$cart['product_qty']}}">
                                </div>
                            </td>
                            <td class="cart_total">
                                <p class="cart_total_price">
                                    {{number_format($subtotal,0,',','.')}}đ

                                </p>
                            </td>
                            <td class="cart_delete">
                                <a class="cart_quantity_delete" href="{{url('/del-product/'.$cart['session_id'])}}"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>

                        @endforeach
                        <tr>
                            <td><input type="submit" value="Cập nhật giỏ hàng" name="update_qty" class="check_out btn btn-default btn-sm"></td>
                            <td><a class="btn btn-default check_out" href="{{url('/del-all-product')}}">Xóa tất cả</a></td>
                            <td>
                                @if(Session::get('customer'))
                                <a class="btn btn-default check_out" href="{{ Session::get('customer') ? URL::to('/checkout') : URL::to('/dang-nhap') }}">
                                    Đặt hàng
                                </a>
                                @endif
                            </td>
                            <td>
                                <?php
                                $customer_id = Session::get('customer_id');
                                if ($customer_id != NULL) {
                                ?>
                                    <a class="btn btn-default check_out" href="{{URL::to('/checkout')}}">Đặt Hàng</a>
                                <?php
                                } else {
                                ?>
                                    <a class="btn btn-default check_out" href="{{URL::to('/dang-nhap')}}">Đặt Hàng</a>
                                <?php
                                }
                                ?>
                            </td>
                            <td colspan="2">
                                <li>Tổng tiền :<span>{{number_format($total,0,',','.')}}đ</span></li>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="5">
                                <center>
                                    @php
                                    echo 'Làm ơn thêm sản phẩm vào giỏ hàng';
                                    @endphp
                                </center>
                            </td>
                        </tr>
                        @endif
                    </tbody>
            </form>
            </table>
        </div>
    </div>
</section> <!--/#cart_items-->
<script type="text/javascript">
    window.onload = function() {
        // Cuộn đến phần tử có id "cart-center" khi trang giỏ hàng tải
        var element = document.getElementById("cart-center");
        if (element) {
            element.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }
</script>

@endsection
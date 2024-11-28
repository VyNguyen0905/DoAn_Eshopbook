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
				<li class=""><a>Thanh toán giỏ hàng</a></li>
				<li class="active"><a href="{{URL::to('/trang-chu')}}">Trang chủ</a></li>
			</ol>
		</div>
		<div class="shopper-informations">
			<div class="row">
				<br>
				<div class="col-sm-12 clearfix">
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
										<td class="image col-3">Hình ảnh</td>
										<td class="description col-3">Tên sản phẩm</td>
										<td class="price col-3">Giá sản phẩm</td>
										<td class="quantity col-3">Số lượng</td>
										<td class="total col-3">Thành tiền</td>
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
											@if(Session::get('coupon'))
											<a class="btn btn-default check_out" href="{{url('/unset-coupon')}}">Xóa mã khuyến mãi</a>
											@endif
										</td>
										<td colspan="2">
											<li>Tổng tiền : <span class="total-amount-text"> </span><span class="total-amount-2">{{number_format($total,0,',','.')}}đ</span></li>
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
						<div class="col-sm-12 clearfix">
							<div class="bill-to">
								<p id="center">Điền thông tin gửi hàng</p>
								<div class="form-one">
									<form method="POST">
										@csrf
										<input type="text" name="shipping_email" class="shipping_email" placeholder="Điền email">
										<input type="text" name="shipping_name" class="shipping_name" placeholder="Họ và tên người nhận hàng">
										<input type="text" name="shipping_address" class="shipping_address" placeholder="Địa chỉ gửi hàng">
										<input type="text" name="shipping_phone" class="shipping_phone" placeholder="Số điện thoại">
										<textarea name="shipping_notes" class="shipping_notes" placeholder="Ghi chú đơn hàng của bạn" rows="5"></textarea>
										<div class="">
											<div class="form-group">
												<label for="exampleInputPassword1">Chọn hình thức thanh toán</label>
												<select name="payment_select" class="form-control input-sm m-bot15 payment_select">
													<option value="0">Qua chuyển khoản</option>
													<option value="1">Tiền mặt</option>
												</select>
											</div>
										</div>
										<div class="col-md-12">
											@if(Session::get('cart') && count(Session::get('cart')) > 0)
											@php
											$vnd = $subtotal/25405;
											@endphp
											<div id="paypal-button"></div>
											<input type="hidden" id="vnd" value="{{round($vnd,2)}}">
											@else
											<!-- Không hiển thị nút PayPal nếu không có sản phẩm trong giỏ hàng -->
											@endif
										</div>
										@if(Session::get('cart') && count(Session::get('cart')) > 0)
										<input type="button" value="Xác nhận đơn hàng" name="send_order" class="btn btn-primary btn-sm send_order">
										@else
										<input type="button" value="Xác nhận đơn hàng" class="btn btn-primary btn-sm send_order" disabled>
										<p style="color: red; text-align: center;">Bạn chưa chọn sản phẩm để mua</p>
										@endif
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section> <!--/#cart_items-->
<script type="text/javascript">
	window.onload = function() {
		// Cuộn đến vị trí có id "center"
		var element = document.getElementById("center");
		if (element) {
			element.scrollIntoView({
				behavior: "smooth",
				block: "center"
			});
		}
	}
</script>
@endsection

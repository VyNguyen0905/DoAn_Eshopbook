<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Shipping;
use App\Order;
use App\OrderDetails;
use App\Customer;
// use App\Coupon;
use App\Product;
use DB;
use Session;

class OrderController extends Controller
{
	public function update_qty(Request $request)
	{
		$data = $request->all();
		$order_details = OrderDetails::where('product_id', $data['order_product_id'])->where('order_code', $data['order_code'])->first();
		$order_details->product_sales_quantity = $data['order_qty'];
		$order_details->save();
	}
	public function update_order_qty(Request $request)
	{
		//update order
		$data = $request->all();
		$order = Order::find($data['order_id']);
		$order->order_status = $data['order_status'];
		$order->save();
		if ($order->order_status == 2) {
			foreach ($data['order_product_id'] as $key => $product_id) {

				$product = Product::find($product_id);
				$product_quantity = $product->product_quantity;
				$product_sold = $product->product_sold;
				foreach ($data['quantity'] as $key2 => $qty) {
					if ($key == $key2) {
						$pro_remain = $product_quantity - $qty;
						$product->product_quantity = $pro_remain;
						$product->product_sold = $product_sold + $qty;
						$product->save();
					}
				}
			}
		} elseif ($order->order_status != 2 && $order->order_status != 3) {
			foreach ($data['order_product_id'] as $key => $product_id) {

				$product = Product::find($product_id);
				$product_quantity = $product->product_quantity;
				$product_sold = $product->product_sold;
				foreach ($data['quantity'] as $key2 => $qty) {
					if ($key == $key2) {
						$pro_remain = $product_quantity + $qty;
						$product->product_quantity = $pro_remain;
						$product->product_sold = $product_sold - $qty;
						$product->save();
					}
				}
			}
		}
	}
	public function print_order($checkout_code)
	{
		$pdf = \App::make('dompdf.wrapper');
		$pdf->loadHTML($this->print_order_convert($checkout_code));

		return $pdf->stream();
	}
	public function print_order_convert($checkout_code)
	{
		$order_details = OrderDetails::where('order_code', $checkout_code)->get();
		$order = Order::where('order_code', $checkout_code)->get();
		foreach ($order as $key => $ord) {
			$customer_id = $ord->customer_id;
			$shipping_id = $ord->shipping_id;
		}
		$customer = Customer::where('customer_id', $customer_id)->first();
		$shipping = Shipping::where('shipping_id', $shipping_id)->first();

		$order_details_product = OrderDetails::with('product')->where('order_code', $checkout_code)->get();

		foreach ($order_details_product as $key => $order_d) {

			$product_coupon = $order_d->product_coupon;
		}
		if ($product_coupon != 'no') {
			$coupon = Coupon::where('coupon_code', $product_coupon)->first();

			$coupon_condition = $coupon->coupon_condition;
			$coupon_number = $coupon->coupon_number;

			if ($coupon_condition == 1) {
				$coupon_echo = $coupon_number . '%';
			} elseif ($coupon_condition == 2) {
				$coupon_echo = number_format($coupon_number, 0, ',', '.') . 'đ';
			}
		} else {
			$coupon_condition = 2;
			$coupon_number = 0;

			$coupon_echo = '0';
		}

		$output = '';

		$output .= '<style>body{
			font-family: DejaVu Sans;
		}
		.table-styling{
			border:1px solid #000;
		}
		.table-styling tbody tr td{
			border:1px solid #000;
		}
		</style>
		<h1><center>Công ty TNHH một thành viên NVS</center></h1>
		<h2><center>Độc lập - Tự do - Hạnh phúc</center></h2>
		<h3 style="color:red;"><center>Hóa Đơn Thu Tiền</center></h3>
		<p>Người đặt hàng</p>
		<table class="table-styling">
				<thead>
					<tr>
						<th>Tên khách đặt</th>
						<th>Số điện thoại</th>
						<th>Email</th>
					</tr>
				</thead>
				<tbody>';
		$output .= '		
					<tr>
						<td>' . $customer->customer_name . '</td>
						<td>' . $customer->customer_phone . '</td>
						<td>' . $customer->customer_email . '</td>
						
					</tr>';
		$output .= '				
				</tbody>
			
		</table>
		<p>Ship hàng tới</p>
			<table class="table-styling">
				<thead>
					<tr>
						<th>Tên người nhận</th>
						<th>Địa chỉ</th>
						<th>Sdt</th>
						<th>Email</th>
						<th>Ghi chú</th>
					</tr>
				</thead>
				<tbody>';
		$output .= '		
					<tr>
						<td>' . $shipping->shipping_name . '</td>
						<td>' . $shipping->shipping_address . '</td>
						<td>' . $shipping->shipping_phone . '</td>
						<td>' . $shipping->shipping_email . '</td>
						<td>' . $shipping->shipping_notes . '</td>		
					</tr>';
		$output .= '				
				</tbody>
		
		</table>
		<p>Đơn hàng đặt</p>
			<table class="table-styling">
				<thead>
					<tr>
						<th>Tên sản phẩm</th>
						<th>Mã giảm giá</th>
						<th>Phí ship</th>
						<th>Số lượng</th>
						<th>Giá sản phẩm</th>
						<th>Thành tiền</th>
					</tr>
				</thead>
				<tbody>';

		$total = 0;
		foreach ($order_details_product as $key => $product) {
			$subtotal = $product->product_price * $product->product_sales_quantity;
			$total += $subtotal;

			if ($product->product_coupon != 'no') {
				$product_coupon = $product->product_coupon;
			} else {
				$product_coupon = 'không mã';
			}
			$output .= '		
					<tr>
						<td>' . $product->product_name . '</td>
						<td>' . $product_coupon . '</td>
						<td>' . number_format($product->product_feeship, 0, ',', '.') . 'đ' . '</td>
						<td>' . $product->product_sales_quantity . '</td>
						<td>' . number_format($product->product_price, 0, ',', '.') . 'đ' . '</td>
						<td>' . number_format($subtotal, 0, ',', '.') . 'đ' . '</td>
						
					</tr>';
		}
		if ($coupon_condition == 1) {
			$total_after_coupon = ($total * $coupon_number) / 100;
			$total_coupon = $total - $total_after_coupon;
		} else {
			$total_coupon = $total - $coupon_number;
		}

		$output .= '<tr>
				<td colspan="2">
					<p>Tổng giảm: ' . $coupon_echo . '</p>
					<p>Phí ship: ' . number_format($product->product_feeship, 0, ',', '.') . 'đ' . '</p>
					<p>Thanh toán : ' . number_format($total_coupon + $product->product_feeship, 0, ',', '.') . 'đ' . '</p>
				</td>
		</tr>';
		$output .= '				
				</tbody>
			
		</table>

		<p>Ký tên</p>
			<table>
				<thead>
					<tr>
						<th width="200px">Người lập phiếu</th>
						
						<th width="800px">Người nhận</th> 
						
					</tr>
				</thead>
				<tbody>';

		$output .= '				
				</tbody>
			
		</table>

		';
		return $output;
	}
	public function view_order($order_code)
	{
		$order_details = OrderDetails::with('product')->where('order_code', $order_code)->get();
		$order = Order::where('order_code', $order_code)->get();
		foreach ($order as $key => $ord) {
			$customer_id = $ord->customer_id;
			$shipping_id = $ord->shipping_id;
			$order_status = $ord->order_status;
		}
		$customer = Customer::where('customer_id', $customer_id)->first();
		$shipping = Shipping::where('shipping_id', $shipping_id)->first();

		$order_details_product = OrderDetails::with('product')->where('order_code', $order_code)->get();
		return view('admin.view_order')
			->with(compact('order_details', 'customer', 'shipping', 'order_details', 'order', 'order_status'));
	}
	public function manage_order()
	{
		$order = Order::orderby('created_at', 'DESC')->get();
		return view('admin.manage_order')->with(compact('order'));
	}

	public function history(Request $request)
	{
		if (Session::get('customer_id')) {
			$cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderby('category_id', 'desc')->get();
			// Lấy danh sách thương hiệu sản phẩm
			$brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderby('brand_id', 'desc')->get();
			// Thay đổi cách lấy sản phẩm để sử dụng sắp xếp cố định
			$all_product = DB::table('tbl_product')
				->where('product_status', '0')
				->orderby('product_id', 'desc') // Sắp xếp theo product_id để cố định thứ tự
				->paginate(80);

			$order = Order::where('customer_id', Session::get('customer_id'))->orderby('order_id', 'desc')->get();

			// Trả về view với các dữ liệu cần thiết
			return view('pages.history.history')
				->with('category', $cate_product)
				->with('brand', $brand_product)
				->with('all_product', $all_product)
				->with('order', $order);
		}
	}
	public function view_history_order(Request $request, $order_code)
	{
		if (Session::get('customer_id')) {
			$cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderby('category_id', 'desc')->get();
			// Lấy danh sách thương hiệu sản phẩm
			$brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderby('brand_id', 'desc')->get();
			// Thay đổi cách lấy sản phẩm để sử dụng sắp xếp cố định
			$all_product = DB::table('tbl_product')
				->where('product_status', '0')
				->orderby('product_id', 'desc') // Sắp xếp theo product_id để cố định thứ tự
				->paginate(80);

			// xem chi tiết lịch sử
			$order_details = OrderDetails::with('product')->where('order_code', $order_code)->get();
			$order = Order::where('order_code', $order_code)->first();

			$customer_id = $order->customer_id;
			$shipping_id = $order->shipping_id;
			$order_status = $order->order_status;

			$customer = Customer::where('customer_id', $customer_id)->first();
			$shipping = Shipping::where('shipping_id', $shipping_id)->first();

			$order_details_product = OrderDetails::with('product')->where('order_code', $order_code)->get();

			// Trả về view với các dữ liệu cần thiết
			//compact('order_details', 'customer', 'shipping', 'order_details', 'order', 'order_status'));
			return view('pages.history.view_history_order')
				->with('category', $cate_product)
				->with('brand', $brand_product)
				->with('all_product', $all_product)
				->with('order_details', $order_details)
				->with('customer', $customer)
				->with('shipping', $shipping)
				->with('order_details', $order_details)
				->with('order', $order)
				->with('order_status', $order_status);
		}
	}
}

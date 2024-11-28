@extends('admin_layout')
@section('admin_content')

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
      Tài khoảng mua hàng
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
            <th>Tên khách hàng</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$customer->customer_name}}</td>
            <td>{{$customer->customer_phone}}</td>
            <td>{{$customer->customer_email}}</td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>
</div>

<br>

<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin vận chuyển hàng
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
    <div class="panel-heading">
      Liệt kê chi tiết đơn hàng
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
            <th style="width:20px;">
              <label class="i-checks m-b-none">
                <input type="checkbox"><i></i>
              </label>
            </th>
            <th>Tên sản phẩm</th>
            <th>Số lượng kho còn</th>
            <!-- <th>Mã giảm giá</th>
            <th>Phí ship hàng</th> -->
            <th>Số lượng</th>
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
            <td><i>{{$i}}</i></td>
            <td>{{$details->product_name}}</td>
            <td>{{$details->product->product_quantity}}</td>
          
            <td>
              <input type="number" min="1" {{$order_status==2 ? 'disabled' : ' ' }} class="order_qty_{{$details->product_id}}" value="{{$details->product_sales_quantity}}" name="product_sales_quantity">
              <input type="hidden" name="order_qty_storage" class="order_qty_storage_{{$details->product_id}}" value="{{$details->product->product_quantity}}">
              <input type="hidden" name="order_code" class="order_code" value="{{$details->order_code}}">
              <input type="hidden" name="order_product_id" class="order_product_id" value="{{$details->product_id}}">
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
          <tr>
            <td colspan="6">
              @foreach($order as $key => $or)
              @if($or->order_status==1)
              <form>
                @csrf
                <select class="form-control order_details">
                  <option id="{{$or->order_id}}" selected value="1">Chưa xử lý</option>
                  <option id="{{$or->order_id}}" value="2">Đã xử lý-Đã giao hàng</option>
                  <option id="{{$or->order_id}}" value="3">Hủy đơn hàng-tạm giữ</option>
                </select>
              </form>
              @elseif($or->order_status==2)
              <form>
                @csrf
                <select class="form-control order_details">
                  <option id="{{$or->order_id}}" value="1">Chưa xử lý</option>
                  <option id="{{$or->order_id}}" selected value="2">Đã xử lý-Đã giao hàng</option>
                  <option id="{{$or->order_id}}" value="3">Hủy đơn hàng-tạm giữ</option>
                </select>
              </form>
              @else
              <form>
                @csrf
                <select class="form-control order_details">
                  <option id="{{$or->order_id}}" value="1">Chưa xử lý</option>
                  <option id="{{$or->order_id}}" value="2">Đã xử lý-Đã giao hàng</option>
                  <option id="{{$or->order_id}}" selected value="3">Hủy đơn hàng-tạm giữ</option>
                </select>
              </form>
              @endif
              @endforeach
              <br>
              @if($order_status!=2&&$order_status!=3&&$order_status!=1)
              <div style="text-align: center; ">
                <button class="btn btn-default update_quantity_order red-button" data-product_id="{{$details->product_id}}" name="update_quantity_order">Cập nhật</button>
              </div>
              @endif
            </td>
          </tr>
        </tbody>
      </table>
      <!-- <a target="_blank" href="{{url('/print-order/'.$details->order_code)}}">In đơn hàng</a> -->
    </div>
  </div>
</div>
@endsection
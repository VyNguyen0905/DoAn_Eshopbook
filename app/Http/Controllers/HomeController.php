<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

session_start();

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderby('category_id', 'desc')->get();
        // Lấy danh sách thương hiệu sản phẩm
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderby('brand_id', 'desc')->get();
        // Thay đổi cách lấy sản phẩm để sử dụng sắp xếp cố định
        $all_product = DB::table('tbl_product')
            ->where('product_status', '0')
            ->orderby('product_id', 'desc') // Sắp xếp theo product_id để cố định thứ tự
            ->paginate(80);

        // Trả về view với các dữ liệu cần thiết
        return view('pages.home')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('all_product', $all_product);
    }

    public function search(Request $request)
    {
        // SEO thông tin
        $meta_desc = "Tìm kiếm sản phẩm";
        $meta_keywords = "Tìm kiếm sản phẩm";
        $meta_title = "Tìm kiếm sản phẩm";
        $url_canonical = $request->url();
        
        // Từ khóa tìm kiếm
        $keywords = $request->keywords_submit;
        
        // Chia từ khóa thành các từ riêng biệt
        $search_terms = explode(' ', $keywords);
    
        // Lấy tất cả các danh mục và thương hiệu để so sánh
        $cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderby('brand_id', 'desc')->get();
        
        // Tìm kiếm sản phẩm dựa trên các từ khóa (tách thành các từ riêng biệt)
        $search_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
            ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id');
        
        foreach ($search_terms as $term) {
            $search_product->orWhere(function($query) use ($term) {
                $query->where('tbl_product.product_name', 'like', '%' . $term . '%')
                      ->orWhere('tbl_category_product.category_name', 'like', '%' . $term . '%')
                      ->orWhere('tbl_brand.brand_name', 'like', '%' . $term . '%');
            });
        }
        // Thực hiện truy vấn
        $search_product = $search_product->get();
        return view('pages.sanpham.search')
            ->with('category', $cate_product)  // Truyền danh mục
            ->with('brand', $brand_product)    // Truyền thương hiệu
            ->with('search_product', $search_product) // Chỉ truyền kết quả sản phẩm
            ->with('meta_desc', $meta_desc)
            ->with('meta_keywords', $meta_keywords)
            ->with('meta_title', $meta_title)
            ->with('url_canonical', $url_canonical);
    }
    
    
}

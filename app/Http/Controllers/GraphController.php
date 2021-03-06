<?php

namespace App\Http\Controllers;

use App\Entities\Page;
use App\Entities\StreamingOrder;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class GraphController extends Controller
{
    private $api;
    public function __construct(Facebook $fb)
    {
        $this->middleware(function ($request, $next) use ($fb) {
            $fb->setDefaultAccessToken(Auth::user()->token);
            $this->api = $fb;
            return $next($request);
        });

    }

    public function graphapi($query, $token)
    {
        
        $response = $this->api->get($query, $token);
        return $response;
    }

    public function retrieveUserProfile()
    {

        $query = '/me/accounts';
        $token = Auth::user()->token;

        try {
            $response = $this->graphapi($query, $token);
            //manage_page
            $item = 0;
            $pages = $response->getGraphEdge()->asArray();
            foreach ($pages as $key) {

                for ($i = 0; $i < count($key['perms']); $i++) {
                    if ($key['perms'][$i] == 'ADMINISTER') {

                        $query = '/' . $key['id'] . '?fields=picture'; //page's pic
                        $response2 = $this->graphapi($query, $token);
                        $graphNode = $response2->getGraphNode();
                        $pic_url = $graphNode['picture']['url'];
                        $page_token = $key['access_token'];

                        $page[$item][0] = $key['name'];
                        $page[$item][1] = $key['id'];
                        $page[$item][2] = $pic_url;
                        $page[$item][3] = $page_token;

                    }
                }
                $item++;
            }

            return view('set_page', ['page' => $page]);
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }

    }

    public function index_load()
    {
        $page = Page::where('fb_id', Auth::user()->fb_id)->first();
        $page_id = $page->page_id;
        $page_token = $page->page_token;
        $query = '/' . $page_id . '/live_videos';
        $query2 = '/' . $page_id . '/videos';
        $token = $page_token;

        try {
            $response = $this->graphapi($query, $token);
            $response2 = $this->graphapi($query2, $token);
            $item = 0;
            $videos = $response->getGraphEdge()->asArray();
            $videos2 = $response2->getGraphEdge()->asArray();
            $video_id = '';
            $post_video_id=$videos2[0]['id'];
            $url = '';
            foreach ($videos as $key) {
                if ($key['status'] == 'LIVE') {
                    $item++;
                    $video_id = $key['id'];
                    $url = $key['embed_html'];
                }
            }
            if ($item>0) {
                return redirect()->action(
                    'GraphController@index_show', ['page_id' => $page_id, 'page_token' => $page_token, 'video_id' => $video_id,'post_video_id' => $post_video_id, 'url' => $url]
                );
            }
            else
            {
                return redirect()->back()->with('fail', '直播尚未開啟！');
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }

    }

    public function index_show(Request $request)
    {
        Header('X-XSS-Protection: 0');
        try {
            $page = Page::where('fb_id', Auth::user()->fb_id)->first();
            $page_id = $page->page_id;
            //iframe
            $url = $request->input('url');
            //comments
            $video_id = $request->input('video_id');
            $post_video_id = $request->input('post_video_id');
            $token = $request->input('page_token');

            $query = '/' . $video_id . '?fields=comments.limit(9999)';
            $response = $this->graphapi($query, $token);
            $comments = $response->getGraphNode();

            if (isset($comments['comments'])) {
                $comments = $comments['comments'];
            }
            else {
                $comments = "";
            }

            return view('index', ['page_id' => $page_id,'url' => $url, 'comments' => $comments, 'video_id' => $video_id,'post_video_id' => $post_video_id, 'token' => $token]);
        } catch (FacebookSDKException $e) {
            return redirect()->action(
                'GraphController@index_load'
            ); // handle exception
        }

    }
    public function update_message(Request $request)
    {
        $video_id = $request->input('video_id');
        $token = $request->input('page_token');

        $query = '/' . $video_id . '?fields=comments.limit(9999)';
        try {
            $response = $this->graphapi($query, $token);
            $comments = $response->getGraphNode();
            if (isset($comments['comments'])) {
                $comments = $comments['comments'];
            } else {
                $comments = "";
            }
            return $comments;
        } catch (FacebookSDKException $e) {
            dd($e);
        }

    }

    //紀錄開始時間
    public function start_record(Request $request)
    {
        $token = $request->input('page_token');
        $post_video_id = $request->input('post_video_id');
        $goods_name = $request->input('goods_name');
        //留言拍賣開始----------------------------------------------------------------------
        $addcomment=$goods_name." 拍賣開始------------------------------";
        $query = '/' . $post_video_id . '/comments';
        $response = $this->api->post($query, array('message' => $addcomment), $token);
        //---------------------------------------------------------------------------------
        $start_time = $request->start_time;
        Session::put('start_time', $start_time);
        return json_encode("");
    }

    //結束競標(+1)
    public function end_record(Request $request)
    {
        $video_id = $request->input('video_id');
        $token = $request->input('page_token');
        $end_time = $request->input('end_time');
        $start_time = Session::get('start_time');
        $getter="";

         //留言結束競標---------------------------------------------------------------------
         $post_video_id = $request->input('post_video_id');
         $goods_name = $request->input('goods_name');
         $comment=$goods_name." 拍賣結束------------------------------";
         $query = '/' . $post_video_id . '/comments';
         $response = $this->api->post($query, array('message' => $comment), $token);
        //---------------------------------------------------------------------------------

        $query = '/' . $video_id . '?fields=comments.order(chronological).limit(9999)';
        try {
            $response = $this->graphapi($query, $token);
            $comments = $response->getGraphNode();
            if (isset($comments['comments'])) {
                $comments = $comments['comments'];
                $temp = array();
                //自動判斷得標
                $item = 0;
                foreach ($comments as $key) {
                    $tmp = $key['created_time']->format('Y-m-d H:i:s');
                    $time = str_replace('T', " ", $tmp);
                    $time = substr($time, 0, 19);
                    $time = date("Y-m-d H:i:s", strtotime("$time +8 hour"));
                    $time2 = strtotime($time);
                    //if留言時間>開始競標時間，開始抓留言判斷產生訂單
                    if ($time2 >= strtotime($start_time) && $time2 <= strtotime($end_time)) {
                        //如果留言contains +
                        if (strpos($key['message'], '+') !== false) {
                            //將+拿掉
                            $num = str_replace('+', " ", $key['message']);
                            //如果是數字的話
                            if (is_numeric($num)) {
                                if($num>0)
                                {
                                    $getter=$getter.' '.$key['from']['name'];
                                    $temp[$item] = array(
                                        'name' => $key['from']['name'],
                                        'id' => $key['from']['id'],
                                        'message' => $key['message'],
                                        'message_time' => $time,
                                        'message_id' => $key['id'],
                                    );
                                }
                            }
                        }
                    }
                    $item++;
                }
                 //留言得標者
                 $post_query = '/'.$post_video_id.'/comments';
                 $post_response = $this->api->post($post_query, array('message' => '得標者為'.$getter), $token);

                 return json_encode($temp,true);
            }
            else
            {
                return json_encode("",true);
            }
        } catch (FacebookSDKException $e) {
            return json_encode($e,true);
        }
    }

    //結束競標(最高價)
    public function end_record_top_price(Request $request)
    {
        $video_id = $request->input('video_id');
        $token = $request->input('page_token');
        $end_time = $request->input('end_time');
        $start_time = Session::get('start_time');

        //留言結束競標
        $post_video_id = $request->input('post_video_id');
        $goods_name = $request->input('goods_name');
        $addcomment=$goods_name." 拍賣結束------------------------------";
        $post_query = '/'.$post_video_id.'/comments';
        $post_response = $this->api->post($post_query, array('message' => $addcomment), $token);
      
        $comment_query = '/' . $video_id . '?fields=comments.order(chronological).limit(9999)';
        try {
            $query_response = $this->graphapi($comment_query, $token);
            $query_result = $query_response->getGraphNode();
            if (isset($query_result['comments'])) {
                $comments = $query_result['comments'];
                $temp = array();
                $top_price = 0;
                $fb_id = "";
                $fb_name = "";
                $message_time = "";
                $message_id = "";

                //自動判斷得標
                foreach ($comments as $key) {
                    $tmp = $key['created_time']->format('Y-m-d H:i:s');
                    $time = str_replace('T', " ", $tmp);
                    $time = substr($time, 0, 19);
                    $time = date("Y-m-d H:i:s", strtotime("$time +8 hour"));
                    $time2 = strtotime($time);
                    if ($time2 >= strtotime($start_time) && $time2 <= strtotime($end_time)) {
                        if (is_numeric($key['message']) && $key['message'] > $top_price) {
                            $top_price = $key['message'];
                            $fb_id = $key['from']['id'];
                            $fb_name = $key['from']['name'];
                            $message_time = $time;
                            $message_id = $key['id'];
                        }
                    }
                }
                $temp[0] = array(
                    [
                        'price' => $top_price,
                        'name' => $fb_name,
                        'id' => $fb_id,
                        'message_time' => $message_time,
                        'message_id' => $message_id,
                    ],
                );
                //留言得標者
                $post_query = '/'.$post_video_id.'/comments';
                $post_response = $this->api->post($post_query, array('message' => '得標者為 '.$fb_name), $token);

                $encoded_json=json_encode($temp,true);
                return $encoded_json;
            }
            else
            {
                return json_encode("",true);
            }
        } catch (FacebookSDKException $e) {
            return json_encode($e,true);
        }
        
    }

    public function store_streaming_order(Request $request)
    {
        $page = Page::where('fb_id', Auth::user()->fb_id)->first();
        $page_id = $page->page_id;
        $page_name= $page->page_name;
        $token = $request->input('page_token');
        $goods_name = $request->input('goods_name');
        $goods_price = $request->input('goods_price');
        $note = $request->input('note');
        $buyer = $request->input('buyer');
        $type = $request->input('type');

        if ($type == 2) //最高價制
        {
            $fb_id= $buyer[0]['id'];
            $goods_num = 1;
            //產生uid
            $time_stamp=time();
            $random_num=rand(100,999);
            $uid=$fb_id.time().$random_num;

            //存入DB
            $page_store = new StreamingOrder();
            $page_store->page_id = $page_id;
            $page_store->page_name = $page_name;
            $page_store->fb_id = $buyer[0]['id'];
            $page_store->name = $buyer[0]['name'];
            $page_store->goods_name =  $goods_name;
            $page_store->goods_price =  $goods_price;
            $page_store->goods_num =  $goods_num;
            $page_store->total_price =  $goods_price;
            $page_store->note =  $note;
            $page_store->comment =  $buyer[0]['comment'];
            $page_store->created_time =  date("Y-m-d H:i:s");
            $page_store->uid = $uid;
            $page_store->save();

            //私訊
            try {
                $url='請至 '.'http://livego.herokuapp.com/buyer_index'.' 結帳，謝謝！';
                $query = '/' . $buyer[0]['message_id'] . '/private_replies';
                $post = $this->api->post($query, array('message' => $url), $token);
                $post2 = $post->getGraphNode()->asArray();
            } catch (FacebookSDKException $e) {
                return json_encode($e, true);
            }
        } else { //+1制
            
            foreach ($buyer as $buyers){
                $fb_id=$buyers['id'];
               //產生uid
                $time_stamp=time();
                $random_num=rand(100,999);
                $uid=$fb_id.time().$random_num;
                //將留言+拿掉
                $num = str_replace('+', "", $buyers['comment']);
                $total_price=(int)($num)*(int)($goods_price);
                //存入資料庫
                $page_store = new StreamingOrder;
                $page_store->page_id = $page_id;
                $page_store->page_name = $page_name;
                $page_store->fb_id = $buyers['id'];
                $page_store->name = $buyers['name'];
                $page_store->goods_name =  $goods_name;
                $page_store->goods_price =  $goods_price;
                $page_store->goods_num =  $num;
                $page_store->total_price =  (string)$total_price;
                $page_store->note =  $note;
                $page_store->comment =  $buyers['comment'];
                $page_store->created_time =  date("Y-m-d H:i:s");
                $page_store->uid = $uid;
                $page_store->save();

                //私訊
                try {
                    $url='請至 '.'http://livego.herokuapp.com/buyer_index'.' 結帳，謝謝！';
                    $query = '/' . $buyers['message_id'] . '/private_replies';
                    $post = $this->api->post($query, array('message' => $url), $token);
                    $post2 = $post->getGraphNode()->asArray();
                } catch (FacebookSDKException $e) {
                   return json_encode($e, true);
                }
            }
        }
        return json_encode(count($buyer),true);
    }

    //私訊
    public function private_reply(Request $request)
    {
        $message_id = $request->input('message_id');
        $reply_text= $request->input('reply_text');
        $token = $request->input('page_token');
        try {
            $query = '/' . $message_id. '/private_replies';
            $post = $this->api->post($query, array('message' =>$reply_text), $token);
            $post2 = $post->getGraphNode()->asArray();
        } catch (FacebookSDKException $e) {
           return json_encode($e, true);
        }

        return json_encode($post);
    }

    //影片留言
    public function add_comment(Request $request)
    {
        $token = $request->input('page_token');
        $post_video_id = $request->input('post_video_id');
        $comment = $request->input('comment');
        $query = '/' . $post_video_id . '/comments';
        $response = $this->api->post($query, array('message' => $comment), $token);
        return json_encode("", true);
    }

    //回覆留言
    public function reply_comment(Request $request)
    {
        $token = $request->input('page_token');
        $post_video_id = $request->input('post_video_id');
        $comment = $request->input('comment');
        $query = '/' . $post_video_id . '/comments';
        $response = $this->api->post($query, array('message' => $comment), $token);
        return json_encode("", true);
    }

}

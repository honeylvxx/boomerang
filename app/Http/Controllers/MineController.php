<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MineController extends Controller
{
    public function getUserInfo(Request $request)
    {
        $uid = Auth::user()->id;
        $user = Auth::user();
        $found_num = Item::where('uid','=',$uid)
            ->where('deleted', 0)
            ->where('lost_type',1)
            ->where('status', 1)
            ->count();
        $lost_num = Item::where('uid','=',$uid)
            ->where('deleted', 0)
            ->where('lost_type',0)
            ->where('status', 1)
            ->count();
        $unfinished_num = Item::where('uid','=',$uid)
            ->where('deleted', 0)
            ->where('status', 0)
            ->count();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'found_num' => $found_num,
            'lost_num' => $lost_num,
            'unfinished_num' => $unfinished_num];

        return  $this->apiReponse(200,null,$data);
    }

    public function found(Request $request)
    {
        $uid = Auth::user()->uid;
        $page = $request->get('page') ? $request->get('page') : 0;
        $items = Item::where('uid','=',$uid)
            ->where('deleted', 0)
            ->where('lost_type',1)
            ->where('status', 1)
            ->select('id','title','description','lost_place','lost_type','images','phone','qq','status','created_at','updated_at')
            ->orderBy('id', 'desc')
            ->skip($page * 10)
            ->take(10)
            ->get();

        return  $this->apiReponse(200,null,$items);
    }

    public function lost(Request $request)
    {
        $page = $request->get('page') ? $request->get('page') : 0;
        $uid = Auth::user()->id;
        $items = Item::where('uid','=',$uid)
            ->where('deleted', 0)
            ->where('lost_type',0)
            ->where('status', 1)
            ->select('id','title','description','lost_place','lost_type','images','phone','qq','status','created_at','updated_at')
            ->orderBy('id', 'desc')
            ->skip($page * 10)
            ->take(10)
            ->get();

        return  $this->apiReponse(200,null,$items);
    }

    public function unfinished(Request $request)
    {
        $page = $request->get('page') ? $request->get('page') : 0;
        $uid = Auth::user()->uid;
        $items = Item::where('uid','=',$uid)
            ->where('deleted', 0)
            ->where('status', 0)
            ->select('id','title','description','lost_place','lost_type','images','phone','qq','status','created_at','updated_at')
            ->orderBy('id', 'desc')
            ->skip($page * 10)
            ->take(10)
            ->get();

        return  $this->apiReponse(200,null,$items);
    }
}

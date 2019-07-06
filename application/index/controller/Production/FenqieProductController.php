<?php


namespace app\index\controller\Production;


use think\Controller;
use think\Db;
use think\facade\Request;

class FenqieProductController extends Controller
{
    public function getFenQieListData()
    {
        $query = Request::param();
        $page = strval($query['page']) - 1;
        $data = Db::table('chick_fenqieDisPath')->limit($page * 10, 10)->select();
        if ($data) {
            foreach ($data as $ke => $value) {
                $data[$ke]['style'] = json_decode($data[$ke]['style']);
            }

            foreach ($data as $key => $DivStyle) {
                foreach ($DivStyle['style'] as $ass => $bbs) {
                    foreach ($bbs as $value => $item) {
                        $arr = [
                            'style' => [
                                'width' => $item . 'px',
                            ]
                        ];
                        $data[$key]['style'][$ass][$value] = $arr;
                    }
                }
            }
        }
        $total = count(Db::table('chick_fenqieDisPath')->field('id')->select());
        return array('data' => $data, 'total' => $total,'page'=>$page);
    }

    public function FenQieListFormData()
    {
        $query = Request::param();
        $id = $query['id'];
        $status = Db::table('chick_fenqieDisPath')->where('id', $id)->update(['dispathDate' => $query['dispathDate'],
            'gavegoodsdate' => $query['gavegoodsdate'], 'hangval' => $query['hangval'], 'productclaim' => $query['productclaim'],
            'remark' => $query['remark']]);
        return array('code' => $status);
    }

    public function isCompleteSync()
    {
        $query = Request::param();
        $id = $query['id'];
        $type = !intval($query['type']);
        $status = Db::table('chick_fenqieDisPath')->where('id', $id)->update(['CompleteCase' => $type]);
        return $status;
    }
}
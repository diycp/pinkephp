<?php
// +----------------------------------------------------------------------
// | 品客PHP框架 [ pinkePHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 浙江蓝酷网络科技有限公司 [ http://www.lankuwangluo.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.pinkephp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace Admin\Controller;

class AdminController extends BackController{

    public function _initialize() {
        parent::_initialize();
    }

    public function index(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/Admin','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/Admin','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('管理员列表')
                ->setSearch(array('username'=>'管理员账号'),'',U('admin/admin/index'))
                ->ajax_url(U('Admin/ajaxAdmin'))
                ->addTopButton('layer',array('data-action'=>'addAdmin','data-width'=>"800px",'data-height'=>'520px','data-title'=>'新增-管理员'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('username', '管理员账号')
                ->addTableColumn('group', '所属分组','function','admin_local_admin_group_name')
                ->addTableColumn('last_time', '最后登录时间','function','common_format_time')
                ->addTableColumn('last_ip', '最后登录IP')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('data-action'=>'editAdmin','data-width'=>"800px",'data-height'=>'520px','data-title'=>'编辑-管理员'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdmin','data-itemname'=>'管理员'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdmin(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增管理员')
                ->setPostUrl(U('Admin/createAdmin'))
                ->addFormItem('username', 'text', '管理员账号')
                ->addFormItem('password', 'password', '密码')
                ->addFormItem('repassword', 'password', '确认密码')
                ->addFormItem('email', 'text', '邮箱')
                ->addFormItem('group', 'select', '所属分组', '所属分组', admin_local_admin_group_name())
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
    public function editAdmin(){
        $id      = I('id',0,'intval');
        $info    = D('Admin/Admin','Datamanager')->getInfo($id);
        $builder = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改管理员')
                ->setPostUrl(U('Admin/saveAdmin'))
                ->addFormItem('username', 'text', '管理员账号')
                ->addFormItem('password', 'password', '密码')
                ->addFormItem('repassword', 'password', '确认密码')
                ->addFormItem('email', 'text', '邮箱')
                ->addFormItem('group', 'select', '所属分组', '所属分组', admin_local_admin_group_name())
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
}
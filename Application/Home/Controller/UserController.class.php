<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends AuthorizedController {
	public function dashboard() {
		$this->display();
	}
	public function userCenter() {
		$uid = session('uid');
		// get the announcement
		$user_center_data['announcement'] = M('Config')->where(array('id' => 1))->getField('announcement');

		// get the available order
		$num =	M('OrderRecord')->where(array('uid' => $uid, 'success' => 0, 'status' => 1, 'success' => 0))->count();
		if ($num) {
			$user_center_data['server_hint'] = '有'.$num.'个套餐待支付，加入售后群 <strong>513222519</strong> 	与管理员联系转账';
		} else {
			$user_center_data['server_hint'] = null;
		}
		$query_condition = array('uid' => $uid, 'success' => 1, 'status' => 0, 'expire_time' => array('gt', date('y-m-d h:i:s')));
		$available_order = D('OrderRecordComboNodeUserView')->where($query_condition)->field('domain_name,method,passwd,expire_time,port,u,d,transfer_enable')->select();
		$user_center_data['server_data'] = $available_order;

		$this->assign('user_center_data', $user_center_data);
		$this->display();
	}
	public function getPurchasedCombo() {
		$uid = session('uid');
		$purchased_combo_data = D('OrderRecordComboView')->where(array('uid' => $uid))->order('purchase_time desc')->select();
		$this->assign('purchased_combo_data', $purchased_combo_data);
		$this->display();
	}
	public function editPersonInfo() {
		if (!I('post.')) {
			$this->display();
		} else {
			$receive['old_password'] = base64_encode(I('post.old_password'));
			$receive['new_password'] = base64_encode(I('post.new_password'));
			if (checkArrayIsNull($receive)) {
				$this->error('修改错误，事情绝对没有那没简单');
			}
			$password = M('Login')->where(array('uid' => session('uid')))->getField('password');
			if ($receive['old_password'] === $password) {
				$data['password'] = $receive['new_password'];
				M('Login')->where(array('uid' => session('uid')))->field('password')->save($data);
				$this->success('密码修改成功');
			} else {
				$this->error('原始密码不正确');
			}
		}
	}
	public function inviteFriends() {
		$this->display();
	}
	public function createInviteCode() {
		// TODO:achieve the function
	}
}

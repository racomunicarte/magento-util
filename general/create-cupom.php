<?php
 
require_once("/var/www/netchairs.com.br/app/Mage.php");
Mage::app();

create();

function delete(){

	for($i=10;$i<=30;$i++){

		$day = str_pad($i, 2, 0, STR_PAD_LEFT);
		$cupom_code = "PNOV2015".$day;

		$model = Mage::getModel('salesrule/rule')
				->getCollection()
				->addFieldToFilter('name', array('eq'=>$cupom_code))
				->getFirstItem();
	 
		$model->delete();
	}
}

function create(){
for($i=10;$i<=30;$i++){
	
	$day = str_pad($i, 2, 0, STR_PAD_LEFT);
	$cupom_code = "PNOV2015".$day;
	$cupom_discount = 5;
	
	$data = array(
		'product_ids' => null,
		'name' => $cupom_code,
		'description' => null,
		'is_active' => 1,
		'website_ids' => array(1),
		'customer_group_ids' => array(0,1,2,3),
		'coupon_type' => 2,
		'coupon_code' => $cupom_code,
		'uses_per_coupon' => 0,
		'uses_per_customer' => 0,
		'from_date' => '2015-11-'.$day,
		'to_date' => '2015-11-'.$day,
		'sort_order' => null,
		'is_rss' => 1,
		'rule' => array(
			'conditions' => array(
				array(
					'type' => 'salesrule/rule_condition_combine',
					'aggregator' => 'all',
					'value' => 1,
					'new_child' => null
				)
			)
		),
		'simple_action' => 'by_percent',
		'discount_amount' => $cupom_discount,
		'discount_qty' => 0,
		'discount_step' => null,
		'apply_to_shipping' => 0,
		'simple_free_shipping' => 0,
		'stop_rules_processing' => 0,
		'rule' => array(
			'actions' => array(
				array(
					'type' => 'salesrule/rule_condition_product_combine',
					'aggregator' => 'all',
					'value' => 1,
					'new_child' => null
				)
			)
		),
		'store_labels' => array('5%')
	);
	 
	$model = Mage::getModel('salesrule/rule');
	//$data = $this->_filterDates($data, array('from_date', 'to_date'));
	 
	$validateResult = $model->validateData(new Varien_Object($data));
	 
	if ($validateResult == true) {
	 
		if (isset($data['simple_action']) && $data['simple_action'] == 'by_percent'
				&& isset($data['discount_amount'])) {
			$data['discount_amount'] = min(100, $data['discount_amount']);
		}
	 
		if (isset($data['rule']['conditions'])) {
			$data['conditions'] = $data['rule']['conditions'];
		}
	 
		if (isset($data['rule']['actions'])) {
			$data['actions'] = $data['rule']['actions'];
		}
	 
		unset($data['rule']);
	 
		$model->loadPost($data);
	 
		$model->save();
		echo "created: ".$cupom_code."\n";
	}
}
}

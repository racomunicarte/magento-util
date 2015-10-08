<?php
/**
 * Create CSV to import into B2W Marketplace
 * */

define("PATH_TO_MAGENTO", "/PATH/TO/MAGENTO");

require_once("Magento.class.php");
require_once("B2w.class.php");

$Magento = new Magento();

$magento_products = $Magento->getAllSableProducts();
$b2w_products = array();

if($magento_products){
	foreach($magento_products as $magento_product){
		$loaded_product = Mage::getModel('catalog/product')->load($magento_product->getId());
		
		$not_include_skus = array("KT001","KT002","KT003","KT004","KT005","KT006");
		
		if(array_search($loaded_product->getSku(), $not_include_skus) !== false){			
			continue;
		}
		
		$b2w = new B2w();
		
		$b2w->ID_PARCEIRO = "CNPJ";
		$b2w->ID_ITEM_PARCEIRO = $loaded_product->getSku();
		$b2w->NOME_ITEM = $loaded_product->getName();
		$b2w->set_PESO_UNITARIO($loaded_product);		
		$b2w->set_ALTURA($loaded_product);
		$b2w->DESCRICAO_ITEM = $loaded_product->getDescription();
		$b2w->set_IMAGEM_ITEM($loaded_product);
		$b2w->set_LARGURA($loaded_product);
		$b2w->set_COMPRIMENTO($loaded_product);
		$b2w->TIPO_ITEM = "E";
		//A = Active, I = Inactive
		$b2w->SITUACAO_ITEM = "A";
		$b2w->PRECO_DE = number_format($loaded_product->getPrice(), 2, ".", "");
		$b2w->PRECO_POR = number_format($loaded_product->getSpecialPrice(), 2, ".", "");
		$b2w->set_QTDE_ESTOQUE($loaded_product);
		//MÓVEIS - COZINHA, ÁREA DE SERVIÇO, JANTAR E JARDIM
		$b2w->DEPARTAMENTO = "9084";
		$b2w->SETOR = "2976";
		$b2w->FAMILIA = "1";
		$b2w->SUB_FAMILIA = "1";
		//0 Nacional, 1 Importação Direta ou 2 Estrangeira - Adq. Mercado Int.
		$b2w->PROCEDENCIA_ITEM = 1;
		$b2w->SKU = $loaded_product->getSku();
		
		$b2w_products[] = $b2w;
		
		//var_dump($b2w); die();
	}	
}

B2w::toCsv("/tmp/b2w.csv", $b2w_products);

die();

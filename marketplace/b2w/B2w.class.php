<?php

class B2w{
	public $ID_PARCEIRO;
	public $ID_ITEM_PARCEIRO;
	public $NOME_ITEM;
	public $PESO_UNITARIO;
	public $ALTURA;
	public $DESCRICAO_ITEM;
	public $IMAGEM_ITEM;
	public $LARGURA;
	public $COMPRIMENTO;
	public $EAN;
	public $ID_ITEM_PAI;
	public $NOME_ITEM_PAI;
	public $TIPO_ITEM;
	public $SITUACAO_ITEM;
	public $PRAZO_XD;
	public $PRECO_DE;
	public $PRECO_POR;
	public $QTDE_ESTOQUE;
	public $DEPARTAMENTO;
	public $SETOR;
	public $FAMILIA;
	public $SUB_FAMILIA;
	public $PROCEDENCIA_ITEM;
	public $SKU;
	public $DESCRITIVO;
	public $TAMANHO;
	public $AUTOR;
	public $COMPOSICAO;
	public $IMAGEM;
	public $COR;
	public $MARCA;
	public $ISBN;
	public $VPN;
	public $TITULO;
	public $VOLTAGEM;
	public $MANUAL;
	
	public function set_PESO_UNITARIO($loaded_product){
		$weight = $loaded_product->getWeight();
		$this->PESO_UNITARIO = number_format($weight*1000, 0, ".", "");
	}
	
	public function set_ALTURA($loaded_product){
		$height = $loaded_product->getResource()
					->getAttribute("embalagem_altura")
					->getFrontend()
					->getValue($loaded_product);
		$this->ALTURA = $this->formatDimension($height);
	}
	
	public function set_LARGURA($loaded_product){
		$width = $loaded_product->getResource()
					->getAttribute("embalagem_largura")
					->getFrontend()
					->getValue($loaded_product);
		$this->LARGURA = $this->formatDimension($width);
	}
	
	public function set_COMPRIMENTO($loaded_product){
		$length = $loaded_product->getResource()
					->getAttribute("embalagem_profundidade")
					->getFrontend()
					->getValue($loaded_product);
		$this->COMPRIMENTO = $this->formatDimension($length);
	}
	
	public function formatDimension($dimension){
		$dimension = str_replace(" cm", "", $dimension);
		$dimension = str_replace(",", ".", $dimension);
		$dimension = number_format($dimension*10, 0, ".", "");
		return $dimension;
	}
	
	public function set_IMAGEM_ITEM($loaded_product){
		$images = array();
		
		// Main image
		$images[] = Mage::getModel('catalog/product_media_config')->getMediaUrl($loaded_product->getImage());
		
		$gallery = Mage::getModel('catalog/product')->load($loaded_product->getId())->getMediaGalleryImages();
		if($gallery){
			foreach($gallery as $image){
				if(!array_search($image->getUrl(), $images)){
					$images[] = $image->getUrl();
				}
				
			}
		}
		$this->IMAGEM_ITEM = implode(",", $images);
	}
	
	public function set_QTDE_ESTOQUE($loaded_product){
		$qtd = (float)Mage::getModel('cataloginventory/stock_item')->loadByProduct($loaded_product)->getQty(); 
		// Only half of stok to marketplace
		$qtd = round($qtd/2, 0, PHP_ROUND_HALF_DOWN);
		$this->QTDE_ESTOQUE = $qtd;
	}
	
	public static function toCsv($file, $content, $separator = "\t"){
		try{
			if (($handle = fopen($file, "w")) !== FALSE) {
				$csv = "";
				
				$header_array = array();
				$header_array[] = "ID_PARCEIRO";
				$header_array[] = "ID_ITEM_PARCEIRO";
				$header_array[] = "NOME_ITEM";
				$header_array[] = "PESO_UNITARIO";
				$header_array[] = "ALTURA";
				$header_array[] = "DESCRICAO_ITEM";
				$header_array[] = "IMAGEM_ITEM";
				$header_array[] = "LARGURA";
				$header_array[] = "COMPRIMENTO";
				$header_array[] = "EAN";
				$header_array[] = "ID_ITEM_PAI";
				$header_array[] = "NOME_ITEM_PAI";
				$header_array[] = "TIPO_ITEM";
				$header_array[] = "SITUACAO_ITEM";
				$header_array[] = "PRAZO_XD";
				$header_array[] = "PRECO_DE";
				$header_array[] = "PRECO_POR";
				$header_array[] = "QTDE_ESTOQUE";
				$header_array[] = "DEPARTAMENTO";
				$header_array[] = "SETOR";
				$header_array[] = "FAMILIA";
				$header_array[] = "SUB_FAMILIA";
				$header_array[] = "PROCEDENCIA_ITEM";
				$header_array[] = "SKU";
				$header_array[] = "DESCRITIVO";
				$header_array[] = "TAMANHO";
				$header_array[] = "AUTOR";
				$header_array[] = "COMPOSICAO";
				$header_array[] = "IMAGEM";
				$header_array[] = "COR";
				$header_array[] = "MARCA";
				$header_array[] = "ISBN";
				$header_array[] = "VPN";
				$header_array[] = "TITULO";
				$header_array[] = "VOLTAGEM";
				$header_array[] = "MANUAL";
				
				$headers = implode($separator, $header_array);
				$csv .= $headers;
				$csv .= "\n";
				
				foreach($content as $line){
					foreach($header_array as $header){
						$csv .= $line->$header.$separator;
					}
					$csv .= "\n";
				}
				
				fwrite($handle, $csv);
				fclose($handle);
				
				echo $file." SAVED";
				return true;
			} else {
				throw("Cannot write file");
			}
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			return false;
		}
	}
}

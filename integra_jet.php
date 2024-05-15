<?php 

$privateKey = 'a0a1047cce70493c9d5d29704f05d0d9';
$apiAccount = '292508153084379141';
$CustomerCode = 'J0086474299';
$senha = 'H5CD3zE6';


$str = strtoupper(md5($senha.'jadada236t2'));
$digestBusiness = base64_encode(pack('H*',md5('J0086027579'.$str.'f60d0110017b4583948e5146b2283efd')));

$BusinessSignature = $digestBusiness;

function getorders($privateKey, $apiAccount, $CustomerCode, $BusinessSignature){

     
    $timestemp = time();

    // URL da API da J&T
    $url = 'https://openapi.jtjms-br.com/webopenplatformapi/api/order/getOrders';

    // Dados da solicitação (neste caso, no formato x-www-form-urlencoded)
	// 2 - numero pedido 
    $data = '{"serialNumber":["codigoPedido"],"digest":"'.$BusinessSignature.'","customerCode":"'.$CustomerCode.'","command":1}';
        
        //Montando o digest do header
        $headerDigest = base64_encode(md5($data.$privateKey, true));

        //Instanciando e enviando a requisição
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'bizContent='.rawurlencode($data),
            CURLOPT_HTTPHEADER => array(
                'timestamp: '.$timestemp,
                'apiAccount: '.$apiAccount,
                'digest: '.$headerDigest,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        //Enviando a requisição e gravando a resposta
        $response = curl_exec($curl);
        
        //Fechando a requisição
        curl_close($curl);
        $array_resp = json_decode($response, true);
		
		$txlogisticId = null;
		$billCode = null;
		$expressType = null;
		$orderType = null;
		$createOrderTime = null;

		if($array_resp['code'] == 1){ 
			foreach($array_resp['data'] as $ar){
				$txlogisticId = $ar['txlogisticId'];
				$billCode = $ar['billCode'];
				$expressType = $ar['expressType'];
				$orderType = $ar['orderType'];
				$createOrderTime = $ar['createOrderTime'];
			}
		}
        //Exibindo a resposta
        echo $response;
}

function tracking($privateKey, $apiAccount){

  
    $timestemp = time();

    // URL da API da J&T
    $url = 'https://openapi.jtjms-br.com/webopenplatformapi/api/logistics/trace';

    // Dados da solicitação (neste caso, no formato x-www-form-urlencoded)
    $data = '{"billCodes":"888888888888"}';
        //Codificando o pedido para envio
        
        //Montando o digest do header
        $headerDigest = base64_encode(md5($data.$privateKey, true));

        //Instanciando e enviando a requisição
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'bizContent='.rawurlencode($data),
            CURLOPT_HTTPHEADER => array(
                'timestamp: '.$timestemp,
                'apiAccount: '.$apiAccount,
                'digest: '.$headerDigest,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        //Enviando a requisição e gravando a resposta
        $response = curl_exec($curl);
        
        //Fechando a requisição
        curl_close($curl);
        echo $response; die;
		$sanTime = null;
		$desc = null;
		$scanType = null;
		$scanNetworkCity = null;
		$scanNetworkArea = null;
		$array_resp = json_decode($response, true);

		//echo ($response);die;
		if($array_resp['code'] == 1){ 
			
			$array_status = $array_resp['data'][0]['details'][0];

			if($array_status['scanType'] == 'assinatura de encomenda'){
				echo $array_status['scanType'];
			}

			foreach($array_resp['data'][0]['details'] as $ar){
				//echo var_dump($ar); die;
				$sanTime = $ar['scanTime'];
				$desc = $ar['desc'];
				$scanType = $ar['scanType'];
				$scanNetworkCity = $ar['scanNetworkCity'];
				$scanNetworkArea = $ar['scanNetworkArea'];
			}
		}
        //Exibindo a resposta
        echo $response;
}

function createorder($privateKey, $apiAccount, $CustomerCode, $BusinessSignature){
	
	//Montando o JSON do envio
	$pedido = '{
		"customerCode":"'.$CustomerCode.'", 
		"digest":"'.$BusinessSignature.'",
		"txlogisticId":"pedido-1111128",
		"expressType":"EZ",
		"orderType":"2",
		"serviceType":"02",
		"deliveryType":"03",
		"sender":{
		"name":"COMERCIO DE MOVEIS DIGITAL - AMO MOVEIS LTDA",
		"company":"AMO MOVEIS",
		"postCode":"86701474",
		"mailBox":"no-email@mail.com.br",
		"taxNumber":"06275524000171",
		"mobile":"1212121212",
		"phone":"121212122",
		"prov":"PR",
		"city":"Arapongas",
		"street":"Rua Drongo",
		"streetNumber":"162",
		"address":"Rua blablalbla, Sala 4",
		"areaCode":"43",
		"ieNumber":"1111111",
		"area":"Vila Cascata"
		},
		"receiver":{
		"name":"Rubia Pedrodo",
		"postCode":"86701474",
		"mailBox":"no-email@mail.com.br",
		"taxNumber":"87862239920",
		"mobile":"43988664740",
		"phone":"43988664740",
		"prov":"PR",
		"city":"Arapongas",
		"street":"Rua Drongo",
		"streetNumber":"162",
		"address":"Rua Drongo, 162",
		"areaCode":"43",
		"ieNumber":"0000000",
		"area":"Vila Cascata"
		},
		"translate":{
		"name":"COMERCIO DE MOVEIS DIGITAL - AMO MOVEIS LTDA",
		"company":"AMO MOVEIS",
		"postCode":"86701474",
		"mailBox":"no-email@mail.com.br",
		"taxNumber":"06275524000171",
		"mobile":"43991090707",
		"phone":"43991090707",
		"prov":"PR",
		"city":"Arapongas",
		"street":"Rua Drongo",
		"streetNumber":"251",
		"address":"Rua Drongo, 162, Sala 4",
		"areaCode":"43",
		"ieNumber":"9085799417",
		"area":"Vila Cascata"
		},
		"goodsType":"bm000008",
		"weight":"8.00",
		"totalQuantity":1,
		"invoiceMoney":"149.99",
		"remark":"CTE emitido para validacao conforme solicitado pelo cliente. Valor de frete informado para fins de referencia. Documento emitido por ME optante pelo simples nacional, nao gera direito a credito de ISS e IPI",
		"items":[
		{
			"itemType":"bm000008",
			"itemName":"DIVERSOS",
			"number":"1",
			"itemValue":"149.99",
			"priceCurrency":"BRL",
			"desc":"DIVERSOS",
			"itemNcm":"00000000"
		}
		],
		"invoiceNumber":"434",
		"invoiceSerialNumber":"1",
		"invoiceMoney":"149.99",
		"taxCode":"0000000000000",
		"invoiceAccessKey":"41230506275524000171550010000004341557749290",
		"invoiceIssueDate":"2023-05-19 15:24:23"
	}';
	
	//Codificando o pedido para envio
	$req_pedido = rawurlencode($pedido);

	//Montando o digest do header
	$headerDigest = base64_encode(md5($pedido.$privateKey, true));

	//Instanciando e enviando a requisição
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://openapi.jtjms-br.com/webopenplatformapi/api/order/addOrder',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => 'bizContent='.$req_pedido,
		CURLOPT_HTTPHEADER => array(
			'timestamp: 1565238848921',
			'apiAccount: '.$apiAccount,
			'digest: '.$headerDigest,
			'Content-Type: application/x-www-form-urlencoded'
		),
	));

	//Enviando a requisição e gravando a resposta
	$response = curl_exec($curl);
	
	//Fechando a requisição
	curl_close($curl);
	$array_resp = json_decode($response, true);
	if($array_resp['code'] == 1){ 
		$trackingID = null;
		$billCode = null;
		$sortCode = $array_resp['data']['sortingCode'];
		foreach($array_resp['data']['orderList'] as $ar){
			$trackingID = $ar['txlogisticId'];
			$billCode = $ar['billCode']; //codigo de rastreio. Ex: U88000118185945
		}
	}
	//Exibindo a resposta
    echo $response;
}



function criar_por_nota($privateKey, $apiAccount, $CustomerCode, $BusinessSignature){
	
	$xml = simplexml_load_file('arquivoXMLdanota.xml');
	$ClientData = $xml->NFe->infNFe;

	$total_quantidade = 0;
	$stringItem = null;
	foreach($ClientData->det as $i){
		$qtd = explode('.', $i->prod->qCom);
		$total_quantidade += $qtd[0];

		$stringItem .= '
		{
			"itemType":"bm000008",
			"itemName":"'.$i->prod->xProd.'",
			"number":"'.$qtd[0].'",
			"itemValue":"'.$i->prod->vProd.'",
			"priceCurrency":"BRL",
			"desc":"'.$i->prod->xProd.'",
			"itemNcm":"'.$i->prod->NCM.'"
		},';
	}
	//Montando o JSON do envio
	$pedido = '{
		"customerCode":"'.$CustomerCode.'", 
		"digest":"'.$BusinessSignature.'",
		"txlogisticId":"'.$xml->protNFe->infProt->chNFe.'",
		"expressType":"EZ",
		"orderType":"2",
		"serviceType":"02",
		"deliveryType":"03",
		"sender":{
		"name":"'.$ClientData->emit->xNome.'",
		"company":"'.$ClientData->emit->xFant.'",
		"postCode":"'.$ClientData->emit->enderEmit->CEP.'",
		"mailBox":"",
		"taxNumber":"'.$ClientData->emit->CNPJ.'",
		"mobile":"'.$ClientData->emit->enderEmit->fone.'",
		"phone":"'.$ClientData->emit->enderEmit->fone.'",
		"prov":"'.$ClientData->emit->enderEmit->UF.'",
		"city":"'.$ClientData->emit->enderEmit->xMun.'",
		"street":"'.$ClientData->emit->enderEmit->xLgr.'",
		"streetNumber":"'.$ClientData->emit->enderEmit->nro.'",
		"address":"'.$ClientData->emit->enderEmit->xCpl.'",
		"areaCode":"'.$ClientData->emit->enderEmit->cPais.'",
		"ieNumber":"'.$ClientData->emit->IE.'",
		"area":"'.$ClientData->emit->enderEmit->xBairro.'"
		},
		"receiver":{
		"name":"'.$ClientData->dest->xNome.'",
		"postCode":"'.$ClientData->dest->enderDest->CEP.'",
		"mailBox":"'.$ClientData->dest->email.'",
		"taxNumber":"'.(isset($ClientData->dest->CPF) ? $ClientData->dest->CPF : $ClientData->dest->CNPJ).'",
		"mobile":"'.((isset($ClientData->dest->enderDest->fone) and $ClientData->dest->enderDest->fone != '') ? $ClientData->dest->enderDest->fone : '99999999').'",
		"phone":"'.((isset($ClientData->dest->enderDest->fone) and $ClientData->dest->enderDest->fone != '') ? $ClientData->dest->enderDest->fone : '99999999').'",
		"prov":"'.$ClientData->dest->enderDest->UF.'",
		"city":"'.$ClientData->dest->enderDest->xMun.'",
		"street":"-",
		"streetNumber":"-",
		"address":"'.$ClientData->dest->enderDest->xLgr.', '.$ClientData->dest->enderDest->nro.', '.((isset($ClientData->dest->enderDest->xCpl) and $ClientData->dest->enderDest->xCpl <> '') ? $ClientData->dest->enderDest->xCpl : '').'",
		"areaCode":"'.$ClientData->dest->enderDest->cPais.'",
		"ieNumber":"'.((strlen($ClientData->dest->CPF) > 11) ? $ClientData->dest->IE : 'ISENTO').'",
		"area":"'.$ClientData->dest->enderDest->xBairro.'"
		},
		"translate":{
		"name":"'.$ClientData->emit->xNome.'",
		"company":"'.$ClientData->emit->xFant.'",
		"postCode":"'.$ClientData->emit->enderEmit->CEP.'",
		"mailBox":"",
		"taxNumber":"'.$ClientData->emit->CNPJ.'",
		"mobile":"'.$ClientData->emit->enderEmit->fone.'",
		"phone":"'.$ClientData->emit->enderEmit->fone.'",
		"prov":"'.$ClientData->emit->enderEmit->UF.'",
		"city":"'.$ClientData->emit->enderEmit->xMun.'",
		"street":"'.$ClientData->emit->enderEmit->xLgr.'",
		"streetNumber":"'.$ClientData->emit->enderEmit->nro.'",
		"address":"'.$ClientData->emit->enderEmit->xCpl.'",
		"areaCode":"'.$ClientData->emit->enderEmit->cPais.'",
		"ieNumber":"'.$ClientData->emit->IE.'",
		"area":"'.$ClientData->emit->enderEmit->xBairro.'"
		},
		"goodsType":"bm000002",
		"weight":"1.00",
		"totalQuantity":'.$total_quantidade.',
		"invoiceMoney":"'.$ClientData->total->ICMSTot->vProd.'",
		"remark":"",
		"items":[
		'.substr($stringItem, 0, -1).'
		],
		"invoiceNumber":"'.$ClientData->ide->nNF.'",
		"invoiceSerialNumber":"'.$ClientData->ide->serie.'",
		"invoiceMoney":"'.$ClientData->total->ICMSTot->vProd.'",
		"taxCode":"0000000000000",
		"invoiceAccessKey":"'.$xml->protNFe->infProt->chNFe.'",
		"invoiceIssueDate":"'.date('Y-m-d H:i:s', strtotime($xml->protNFe->infProt->dhRecbto)).'"
	}';


	
		
		$req_pedido = rawurlencode($pedido);

		//Montando o digest do header
		$headerDigest = base64_encode(md5($pedido.$privateKey, true));

		//Instanciando e enviando a requisição
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://openapi.jtjms-br.com/webopenplatformapi/api/order/addOrder',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => 'bizContent='.$req_pedido,
			CURLOPT_HTTPHEADER => array(
				'timestamp: 1565238848921',
				'apiAccount: '.$apiAccount,
				'digest: '.$headerDigest,
				'Content-Type: application/x-www-form-urlencoded'
			),
		));

		//Enviando a requisição e gravando a resposta
		$response = curl_exec($curl);
		
		//Fechando a requisição
		curl_close($curl);
		$array_resp = json_decode($response, true);
		ECHO $array_resp['code'];
		if($array_resp['code'] == 1){ 
			$trackingID = null;
			$billCode = null;
			$sortCode = $array_resp['data']['sortingCode'];
			foreach($array_resp['data']['orderList'] as $ar){
				$trackingID = $ar['txlogisticId'];
				$billCode = $ar['billCode']; //codigo de rastreio. Ex: U88000118185945
			}
		}

		echo $sortCode.'|'.$response.'|'.$trackingID.'|'.$billCode;

}   

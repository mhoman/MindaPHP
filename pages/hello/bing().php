<?php 
$query = isset($_POST['q'])?$_POST['q']:'';

if (!($results = Cache::get($query))) {
	$results = array();
	if ($query) {
		if (Curl::call('GET','http://www.bing.com/search',array('q'=>$query),$result)==200) {
			
			$dom = new DOMDocument();
			@$dom->loadHTML($result);
			
			$xpath = new DOMXpath($dom);
			$elements = $xpath->query('//ol["b_results"]/li[@class="b_algo"]//h2/a');
	
			foreach ($elements as $element) {
				$text = $element->nodeValue;
				$link = $element->getAttribute("href");
				$results[] = compact('text','link'); 
			}
			Cache::set($query,$results,60);
		}
	}
}
<?php

	// 创建批处理cURL句柄
	$mh = curl_multi_init();


	for($i=2015211456, $j=2015211457; $i<2015211466; $i+=2,$j=$i+1){

		// 创建一对cURL资源
		$ch1 = curl_init();
		$ch2 = curl_init();

		curl_setopt($ch1, CURLOPT_URL, "http://jwzx.cqupt.edu.cn/showstuPic.php?xh={$i}");
		curl_setopt($ch2, CURLOPT_URL, "http://jwzx.cqupt.edu.cn/showstuPic.php?xh={$j}");
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1, CURLOPT_HEADER, 0);
		curl_setopt($ch2, CURLOPT_HEADER, 0);

		// 增加2个句柄
		curl_multi_add_handle($mh,$ch1);
		curl_multi_add_handle($mh,$ch2);

		//运行状态
		$still_running = null;

		// 执行批处理句柄
		do {
			usleep(10000);
			curl_multi_exec($mh, $still_running);
			$data[$i] = curl_multi_getcontent($ch1);
			$data[$j] = curl_multi_getcontent($ch2);
			foreach ($data as $key => $value) {
				$file = fopen("{$key}.jpg",'wb');
				fwrite($file, $value);
				fclose($file);
			}
		}while($still_running > 0);


	}

	curl_multi_close($mh);
<?php
function fix_wz_data($article) {
	if (empty($article->json)) {
		return;
	}
	if ($article->category_id != \App\Category::where('name', '王者荣耀')->first()->id) {
		return;
	}

	$data = json_decode($article->json, 1);
	$ming_items = [];
	if (!empty($data['铭文搭配建议'])) {
		$ming_ids = explode('|', $data['铭文搭配建议']);
		// dd($ming_ids);
		$ming_db = json_decode(file_get_contents(public_path('/json/wzry/ming.json')));
		foreach ($ming_ids as $ming_id) {
			foreach ($ming_db as $ming_row) {
				if ($ming_row->ming_id == $ming_id) {
					$ming_row->image_url = 'http://game.gtimg.cn/images/yxzj/img201606/mingwen/' . $ming_id . '.png';
					$ming_items[] = $ming_row;
				}
			}
		}
	}
	$data['铭文搭配建议'] = $ming_items;

	if (!empty($data['英雄关系'])) {
		$relations = [];
		foreach ($data['英雄关系'] as $index => $relation) {
			if ($index == 0) {
				$item['title'] = '最佳搭档';
			}
			if ($index == 1) {
				$item['title'] = '被谁克制';
			}
			if ($index == 2) {
				$item['title'] = '克制谁';
			}
			$item['items'] = [];
			foreach ($relation[1] as $r) {
				$item['items'][] = [
					'img' => $r[0],
					'desc' => $r[1],
				];
			}
			$relations[] = $item;
		}
		$data['英雄关系'] = $relations;
	}
	$article->data_wz = $data;
}
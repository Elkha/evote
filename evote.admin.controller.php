<?php

/**
 * 추천 제한
 *
 * Copyright (c) 엘카
 *
 * Generated with https://www.poesis.org/tools/modulegen/
 */
class EvoteAdminController extends Evote
{
	/**
	 * 관리자 설정 저장 액션 예제
	 */
	public function procEvoteAdminInsertConfig()
	{
		// 현재 설정 상태 불러오기
		//$config = $this->getConfig();
		$config = new stdClass();

		// 제출받은 데이터 불러오기
		$vars = Context::getRequestVars();
		$prefix = array('doc','cmt','doc2','cmt2');
		foreach($prefix as $v)
		{
			$args[] = $v . '_minutes_ALL';
			$args[] = $v . '_counts_ALL';
			$args[] = $v . '_minutes_MEM';
			$args[] = $v . '_counts_MEM';
		}
		foreach($args as $val)
		{
			if(isset($vars->{$val}))
			{
				$config->{$val} = (INT)$vars->{$val};
				unset($vars->{$val});
			}
		}

		foreach($prefix as $v)
		{
			$config->{$v.'_modules'} = array();
			foreach($vars as $key => $val)
			{
				if(preg_match('/^'.$v.'_minutes_([0-9]+)$/', $key, $matches))
				{
					$i = $matches[1];
					if(isset($vars->{$v."_mid_$i"}) && isset($vars->{$v."_counts_$i"}))
					{
						$regex = isset($vars->{$v."_regex_$i"}) && $vars->{$v."_regex_$i"}=='Y'? 'Y' : 'N';
						$mid = htmlspecialchars(strip_tags($vars->{$v."_mid_$i"})); // security
						// mid, minutes, counts, Y
						$config->{$v.'_modules'}[] = array($mid, (INT)$val, (INT)$vars->{$v."_counts_$i"}, $regex);
					}
				}
			}
		}

		// 변경된 설정을 저장
		$output = $this->setConfig($config);
		if (!$output->toBool())
		{
			return $output;
		}

		// 설정 화면으로 리다이렉트
		$this->setMessage('success_registed');
		$this->setRedirectUrl(Context::get('success_return_url'));
	}
}

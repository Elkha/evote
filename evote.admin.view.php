<?php

/**
 * 추천 제한
 * 
 * Copyright (c) 엘카
 * 
 * Generated with https://www.poesis.org/tools/modulegen/
 */
class EvoteAdminView extends Evote
{
	/**
	 * 초기화
	 */
	public function init()
	{
		// 관리자 화면 템플릿 경로 지정
		$this->setTemplatePath($this->module_path . 'tpl');
	}
	
	/**
	 * 관리자 설정 화면 예제
	 */
	public function dispEvoteAdminConfig()
	{
		// 현재 설정 상태 불러오기
		$config = $this->getConfig();
		
		// Context에 세팅
		Context::set('evote_config', $config);
		
		// 스킨 파일 지정
		$this->setTemplateFile('config');
	}
}

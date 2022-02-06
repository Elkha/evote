<?php

/**
 * 추천 제한
 *
 * Copyright (c) 엘카
 *
 * Generated with https://www.poesis.org/tools/modulegen/
 */
class EvoteController extends Evote
{
	var $doc_cache = array();
	var $cmt_cache = array();
	public function triggerAfterVoteDocument($obj)
	{
		$logged_info = Context::get('logged_info');
		if(!is_object($logged_info) || !$logged_info->member_srl)
		{
			return $this->createObject();
		}
		$config = $this->getConfig();
		foreach($this->doc_cache as $val)
		{
			if($val->document_srl==$obj->document_srl)
			{
				$type = $obj->point < 0? 'doc2' : 'doc';
				$cache_key = 'object_' . $type . $logged_info->member_srl;
				$cache_val = (array)$this->_get($cache_key);
				$args = new stdClass();
				$args->member_srl = abs($obj->member_srl);
				$args->time = time();
				$args->module_srl = $val->module_srl;

				if(isset($config->cache_expire))
				{
					foreach($cache_val as $k => $v)
					{
						if(time() - $config->cache_expire < $v->time)
						{
							continue;
						}
						unset($cache_val[$k]);
					}
				}
				$cache_val[] = $args;
				$this->_put($cache_key, $cache_val);
			}
		}
	}
	public function triggerBeforeVoteDocument($obj)
	{
		$type = $obj->point < 0? 'doc2' : 'doc';
		$output = $this->_check(abs($obj->member_srl), $obj->module_srl, 'doc');
		if(!$output->toBool())
		{
			return $output;
		}
		$this->doc_cache[] = $obj;
	}
	public function triggerAfterVoteComment($obj)
	{
		$logged_info = Context::get('logged_info');
		if(!is_object($logged_info) || !$logged_info->member_srl)
		{
			return $this->createObject();
		}
		$config = $this->getConfig();
		foreach($this->cmt_cache as $val)
		{
			if($val->comment_srl==$obj->comment_srl)
			{
				$type = $obj->point < 0? 'cmt2' : 'cmt';
				$cache_key = 'object_' . $type . '_' . $obj->comment_srl . '_' . $logged_info->member_srl;
				$cache_val = (array)$this->_get($cache_key);
				$args = new stdClass();
				$args->member_srl = abs($obj->member_srl);
				$args->time = time();
				$args->module_srl = $val->module_srl;

				if(isset($config->cache_expire))
				{
					foreach($cache_val as $k => $v)
					{
						if(time() - $config->cache_expire < $v->time)
						{
							continue;
						}
						unset($cache_val[$k]);
					}
				}
				$cache_val[] = $args;
				$this->_put($cache_key, $cache_val);
			}
		}
	}
	public function triggerBeforeVoteComment($obj)
	{
		$type = $obj->point < 0? 'cmt2' : 'cmt';
		$output = $this->_check(abs($obj->member_srl), $obj->module_srl, $type);
		if(!$output->toBool())
		{
			return $output;
		}
		$this->cmt_cache[] = $obj;
	}
	protected function _check(int $author_member_srl, int $module_srl, $prefix)
	{
		$logged_info = Context::get('logged_info');
		if(!is_object($logged_info) || !$logged_info->member_srl)
		{
			return $this->createObject();
		}

		$oModuleModel = getModel('module');
		$module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);

		$type = strpos($prefix, 'doc')===0? 'doc' : 'cmt';
		$do = $type==$prefix? '추천' : '비추';
		$cache_key = 'object_' . $prefix . $logged_info->member_srl;
		$cache_val = (array)$this->_get($cache_key);
		$config = $this->getConfig();

		$count_ALL = 0;
		$term_ALL = 0;
		$count_MEM = 0;
		$term_MEM = 0;
		$count_modules = array();
		$term_modules = array();

		foreach($cache_val as $val)
		{
			if(($_term = time() - $config->{$prefix.'_minutes_ALL'} * 60 - $val->time) <= 0)
			{
				if($term_ALL < abs($_term))
				{
					$term_ALL = abs($_term);
				}
				$count_ALL++;
			}
			if($author_member_srl && $val->member_srl==$author_member_srl && ($_term = time() - $config->{$prefix.'_minutes_MEM'} * 60 - $val->time) <= 0)
			{
				if($term_MEM < abs($_term))
				{
					$term_MEM = abs($_term);
				}
				$count_MEM++;
			}
			if($val->module_srl==$module_info->module_srl && isset($config->{$type.'_modules'}))
			{
				foreach($config->{$type.'_modules'} as $k => $v)
				{
					if($v[3]=='Y' && !preg_match($v[0], $module_info->mid))
					{
						continue;
					}
					if($v[3]!='Y' && $v[0]!=$module_info->mid)
					{
						continue;
					}
					if(($_term = time() - $v[1] * 60 - $val->time) <= 0)
					{
						if(!isset($term_modules[$k]))
						{
							$term_modules[$k] = 0;
						}
						if(!isset($count_modules[$k]))
						{
							$count_modules[$k] = 0;
						}
						if($term_modules[$k] < abs($_term))
						{
							$term_modules[$k] = abs($_term);
						}
						$count_modules[$k]++;
						if($v[2] <= $count_modules[$k])
						{
							$term = ceil($term_modules[$k] / 60) >= 60? round($term_modules[$k] / 3600) . ' 시간' : ceil($term_modules[$k] / 60) . ' 분';
							return $this->createObject(-1, "이 게시판에 $term 후 $do 할 수 있습니다.");
						}
					}
				}
			}
		}
		if(isset($config->{$prefix.'_counts_MEM'}) && $config->{$prefix.'_counts_MEM'} <= $count_MEM)
		{
			$term = ceil($term_MEM / 60) >= 60? round($term_MEM/3600) . ' 시간' : ceil($term_MEM / 60) . ' 분';
			return $this->createObject(-1, "이 회원에게 $term 후 $do 할 수 있습니다.");
		}
		if(isset($config->{$prefix.'_counts_ALL'}) && $config->{$prefix.'_counts_ALL'} <= $count_ALL)
		{
			$term = ceil($term_ALL / 60) >= 60? round($term_ALL/3600) . ' 시간' : ceil($term_ALL / 60) . ' 분';
			return $this->createObject(-1, "$term 후 $do 할 수 있습니다.");
		}
		return $this->createObject();
	}
	protected function _put($cache_key, $cache_val)
	{
		$config = $this->getConfig();
		if(!isset($config->cache_expire))
		{
			return;
		}
		$oCacheHandler = CacheHandler::getInstance();
		if($oCacheHandler->isSupport())
		{
			$this->setCache($cache_key, $cache_val, $config->cache_expire);
		}
		else
		{
			FileHandler::writeFile("files/cache/evote/$cache_key.php", '<'.'?php exit; ?>' . json_encode($cache_val));
		}
	}
	protected function _get($cache_key)
	{
		$config = $this->getConfig();
		if(!isset($config->cache_expire))
		{
			return new stdClass();
		}
		$oCacheHandler = CacheHandler::getInstance();
		if($oCacheHandler->isSupport())
		{
			$cache_val = $this->getCache($cache_key);
			if(is_object($cache_val) || is_array($cache_val))
			{
				return $cache_val;
			}
		}
		// use file cache
		else if(file_exists(_XE_PATH_ . ($filename = "files/cache/evote/$cache_key.php")))
		{
			return json_decode(str_replace('<'.'?php exit; ?>', '', FileHandler::readFile($filename)));
		}
		return NULL;
	}

	/**
	 * 트리거 예제: 새 글 작성시 실행
	 *
	 * 주의: 첨부파일이 있는 경우 아직 작성하지 않았어도 이 함수가 실행될 수 있음
	 *
	 * @param object $obj
	 */
	public function triggerAfterInsertDocument($obj)
	{

	}

	/**
	 * 트리거 예제: 글 수정시 실행
	 *
	 * 주의: 첨부파일이 있는 경우 최종 작성 시점에 이 함수가 실행될 수 있음
	 *
	 * @param object $obj
	 */
	public function triggerAfterUpdateDocument($obj)
	{

	}

	/**
	 * 트리거 예제: 글 삭제시 실행
	 *
	 * @param object $obj
	 */
	public function triggerAfterDeleteDocument($obj)
	{

	}

	/**
	 * 트리거 예제: 새 댓글 작성시 실행
	 *
	 * 주의: 첨부파일이 있는 경우 아직 작성하지 않았어도 이 함수가 실행될 수 있음
	 *
	 * @param object $obj
	 */
	public function triggerAfterInsertComment($obj)
	{

	}

	/**
	 * 트리거 예제: 댓글 수정시 실행
	 *
	 * 주의: 첨부파일이 있는 경우 최종 작성 시점에 이 함수가 실행될 수 있음
	 *
	 * @param object $obj
	 */
	public function triggerAfterUpdateComment($obj)
	{

	}

	/**
	 * 트리거 예제: 댓글 삭제시 실행
	 *
	 * @param object $obj
	 */
	public function triggerAfterDeleteComment($obj)
	{

	}
}

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
	public function triggerAfterVoteDocument($obj)
	{
	}
	public function triggerBeforeVoteDocument($obj)
	{
	}
	public function triggerAfterVoteComment($obj)
	{
	}
	public function triggerBeforeVoteComment($obj)
	{
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

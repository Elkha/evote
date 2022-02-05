# evote
- 새벽에 간단히 만들어 본 모듈입니다.
- 문제: 무분별한 추천
- 해결: 문서, 댓글의 추천 수 제한
- 도움: 모듈 생성기 - https://www.poesis.org/tools/modulegen/

## 각 회원은 [문서] 추천에 대하여 아래 제한을 받습니다.
1. n 시간 동안 추천 수 합 m 까지 제한 (예: 24시간 동안 20개)
2. n 시간 동안 게시판 a 에 한해 추천 수 합 m 개 까지 제한 (예: 24시간 동안 5개)
3. n 시간 동안 회원 x 에게 추천 수 합 m 개 까지 제한 (예: 24시간 동안 3개)

## 각 회원은 [댓글] 추천에 대하여 아래 제한을 받습니다.
1. n 시간 동안 추천 수 합 m 까지 제한 (예: 24시간 동안 10개)
2. n 시간 동안 게시판 a 에 한해 추천 수 합 m 개 까지 제한 (예: 24시간 동안 3개)
3. n 시간 동안 회원 x 에게 추천 수 합 m 개 까지 제한 (예: 24시간 동안 3개)

## 각 회원은 [문서] 비추에 대하여 아래 제한을 받습니다.
1. n 시간 동안 추천 수 합 m 까지 제한 (예: 24시간 동안 10개)
2. n 시간 동안 게시판 a 에 한해 추천 수 합 m 개 까지 제한 (예: 24시간 동안 5개)
3. n 시간 동안 회원 x 에게 추천 수 합 m 개 까지 제한 (예: 24시간 동안 3개)

## 각 회원은 [댓글] 비추에 대하여 아래 제한을 받습니다.
1. n 시간 동안 추천 수 합 m 까지 제한 (예: 24시간 동안 10개)
2. n 시간 동안 게시판 a 에 한해 추천 수 합 m 개 까지 제한 (예: 24시간 동안 5개)
3. n 시간 동안 회원 x 에게 추천 수 합 m 개 까지 제한 (예: 24시간 동안 3개)

## 구현 원리
추천 로그 테이블에 module_srl 인덱스가 없습니다.   
member_srl + regdaet 멀티 인덱스도 없습니다.   
따라서 object cache(=memcached, apcu) 이용하여 구현 했습니다.   
웹 호스팅과 같이 object cache 활성화되지 않았다면 files/cache/evote/ 경로에 캐시 파일을 씁니다.   
사이트 성능에 영향을 끼치지 않게 설계 하였습니다.

## 트리거
- document.updateVotedCount (before, after)
- comment.updateVotedCount (before, after)

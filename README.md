Possibilities:
- it's can validate URL by checking response code from a server (allow all codes except 4** and 5**. But you can change it by editing an entity Check.php;
- generating a short url;
- also you may type a desired URL and if this one will be available you will get it;
- application contains amount os uses shorten urls in DB;
If you need to remove URLs from DB through some days you can add this event for DB (SQL):
  DROP EVENT `e_15days`; 
  CREATE DEFINER=`root`@`%` EVENT `e_15days` 
  ON SCHEDULE EVERY 1 DAY STARTS '2018-02-23 01:23:11' 
  ON COMPLETION NOT PRESERVE ENABLE 
  DO DELETE FROM url WHERE CURRENT_DATE - CONVERT(created, DATE) >= 3

set "Ymd=%date:~,4%%date:~5,2%%date:~8,2%"
mysqldump --opt -uroot -p112233 amzcount > D:\db_backup\amzcount_%Ymd%.sql


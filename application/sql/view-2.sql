CREATE view center_students_nutritions as SELECT t1.id, t1.student_id, t1.date_weighing,t1.wfa,t1.hfa,t1.wfh, t3.student_name,t3.year_id,t3.worker_id FROM e_weighing AS t1 INNER JOIN (SELECT student_id, MAX(date_weighing) AS MaxDate FROM e_weighing GROUP BY student_id) AS t2 ON (t1.student_id = t2.student_id AND t1.date_weighing = t2.MaxDate) JOIN center_students_schoolyear t3 ON t1.student_id = t3.student_id;
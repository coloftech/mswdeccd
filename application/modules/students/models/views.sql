CREATE VIEW center_students_schoolyear as SELECT epupils.pupilsId as student_id,eworkers.centerId as center_id,eworkers.workersId as worker_id,eschoolyear.YearId AS year_id,StudentType as student_type,concat(epupils.lName,', ',epupils.fName,' ',epupils.mName,' ',epupils.ext) as student_name,YearStart as class_start,YearEnd as class_end FROM `epupils` JOIN eschoolyear_by_worker_students ON studentId = pupilsId JOIN eschoolyear_by_worker ON eschoolyear_by_worker.workersId = eschoolyear_by_worker_students.workersId JOIN eworkers ON eworkers.workersId = eschoolyear_by_worker.workersId JOIN eschoolyear ON eschoolyear.YearId = eschoolyear_by_worker_students.YearId;

CREATE view center_workers_schoolyear as SELECT eworkers.workersId as worker_id, eworkers.centerId as center_id, eschoolyear.YearId as year_id, CONCAT(eworkers.lName, ', ', eworkers.fName, ' ', eworkers.mName, ' ' , eworkers.ext) as worker_name,eworkers.address as worker_address,eschoolyear.YearStart as class_start, eschoolyear.YearEnd as class_end,ecenter.centerName as center_name, eworkers.jobStatus as job_status,eschoolyear.Status as class_status,(SELECT COUNT(*) FROM eschoolyear_by_worker_students where eschoolyear_by_worker_students.workersId = eworkers.workersId and eschoolyear_by_worker_students.YearId = eschoolyear_by_worker.YearId) AS total_students FROM eworkers JOIN eschoolyear_by_worker ON eschoolyear_by_worker.workersId = eworkers.workersId JOIN eschoolyear ON eschoolyear.YearId = eschoolyear_by_worker.YearId JOIN ecenter on ecenter.centerId = eworkers.centerId;


CREATE VIEW center_schoolyear as SELECT ecenter.centerId as center_id,centerName as center_name,ecenter.centerAddress as center_address, (SELECT count(*) from center_students_schoolyear WHERE center_students_schoolyear.center_id = ecenter.centerId) as total_students, (SELECT count(*) FROM center_workers_schoolyear WHERE center_workers_schoolyear.center_id = ecenter.centerId) as total_workers FROM ecenter;

CREATE VIEW eworkers_inactive AS SELECT workersId as worker_id,centerId AS centerId,CONCAT(eworkers.lName, ', ', eworkers.fName, ' ', eworkers.mName, ' ' , eworkers.ext) as worker_name FROM eworkers where workersId NOT IN (SELECT workersId from eschoolyear_by_worker);

create view center_workers as SELECT DISTINCT eworkers.workersId as worker_id, eworkers.centerId as center_id, CONCAT(eworkers.lName, ', ', eworkers.fName, ' ', eworkers.mName, ' ' , eworkers.ext) as worker_name,ecenter.centerName as center_name,eworkers.address AS worker_address,eworkers.jobStatus as job_status FROM eworkers lEFT JOIN ecenter ON eworkers.centerId = ecenter.centerId;


	create view estudents as SELECT DISTINCT cs.*,ep.birthdate,ep.gender,ep.barangay FROM center_students_schoolyear cs JOIN epupils ep on cs.student_id = ep.pupilsId GROUP BY student_id ORDER BY year_id DESC;
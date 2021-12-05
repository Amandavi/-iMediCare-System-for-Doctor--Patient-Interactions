<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-05-11 08:09:17 --> 404 Page Not Found: Css/bootstrap-datepicker3.min.css
ERROR - 2021-05-11 08:09:17 --> 404 Page Not Found: Css/bootstrap.css.map
ERROR - 2021-05-11 08:09:17 --> 404 Page Not Found: Css/bootstrap.min.css.map
ERROR - 2021-05-11 09:48:56 --> Query error: Unknown column 'tbl_mrn_issues.mrn_id' in 'on clause' - Invalid query:  select tbl_mrn_issues.*  , tbl_mrns.code as mrn_code   , tbl_mrns.mrn_date as mrn_date   , ( case   when   tbl_mrn_issues.status = '1' then 'Draft' when   tbl_mrn_issues.status = '2' then 'Pending authorized' when   tbl_mrn_issues.status = '3' then 'Rejected' when   tbl_mrn_issues.status = '4' then 'Authorized' when   tbl_mrn_issues.status = '5' then 'Cancelled' else 'Draft' END ) as Status_Flag from tbl_mrn_issues inner join tbl_mrns on tbl_mrns.mrn_id =  tbl_mrn_issues.mrn_id where mrn_issue_id =23
ERROR - 2021-05-11 09:49:11 --> 404 Page Not Found: Mrn-issue/index
ERROR - 2021-05-11 10:03:49 --> 404 Page Not Found: Css/bootstrap-datepicker3.min.css
ERROR - 2021-05-11 14:09:49 --> 404 Page Not Found: Css/bootstrap-datepicker3.min.css

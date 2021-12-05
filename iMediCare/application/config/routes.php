<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['login'] = 'admin/Login/LoginController/index';
$route['login-sumbit'] = 'admin/Login/LoginController/loginSubmit';
$route['login-out'] = 'admin/Login/LoginController/logOut';

$route['register'] = 'admin/Login/LoginController/register';
$route['register-sumbit'] = 'admin/Login/LoginController/registerSubmit';
$route['register-thank-you'] = 'admin/Login/LoginController/thankYou';


$route['appointment'] = 'admin/Appointment/AppointmentController/index';
$route['appointment-sumbit'] = 'admin/Appointment/AppointmentController/appointmentSubmit';

$route['profile'] = 'admin/Profile/ProfileController/index';
$route['profile-sumbit'] = 'admin/Profile/ProfileController/profileSubmit';

$route['administration'] = 'admin/Dashboard/DashboardController/dashboard';
$route['appointment-list'] = 'admin/Dashboard/DashboardController/appointmentsList';
$route['appointment-new'] = 'admin/Dashboard/DashboardController/appointmentNew';
$route['appointment-save'] = 'admin/Dashboard/DashboardController/appointmentSubmit';
$route['appointment-load'] = 'admin/Dashboard/DashboardController/appointmentLoad';
$route['appointment-search'] = 'admin/Dashboard/DashboardController/appointmentSearch';

$route['channelling-details'] = 'admin/Dashboard/DashboardController/channellingDetails';
$route['channelling-doc'] = 'admin/Dashboard/DashboardController/channellingDocUpload';
$route['channelling-doc-delete'] = 'admin/Dashboard/DashboardController/channellingDocDelete';
$route['channelling-load/(:any)'] = 'admin/Dashboard/DashboardController/channellingLoad/$1';
$route['doctor-recommends'] = 'admin/Dashboard/DashboardController/doctorRecommends';



$route['change-password'] = 'admin/Dashboard/DashboardController/changePassword';
$route['password-submit'] = 'admin/Dashboard/DashboardController/passwordSubmit';



$route['medical-reports'] = 'admin/Dashboard/DashboardController/medicalReports';
$route['medical-reports-search'] = 'admin/Dashboard/DashboardController/searchMedicalReport';
$route['medical-reports-new'] = 'admin/Administration/AdministrationController/newMedicalReport';
$route['medical-reports-save'] = 'admin/Administration/AdministrationController/saveMedicalReport';
$route['medical-reports-edit'] = 'admin/Administration/AdministrationController/editMedicalReport';

$route['doctors'] = 'admin/Administration/AdministrationController/doctorList';
$route['new-doctor'] = 'admin/Administration/AdministrationController/newDoctor';
$route['save-doctor'] = 'admin/Administration/AdministrationController/saveDoctor';
$route['edit-doctor'] = 'admin/Administration/AdministrationController/editDoctor';

$route['user'] = 'admin/Administration/AdministrationController/userList';
$route['new-user'] = 'admin/Administration/AdministrationController/newUser';
$route['save-user'] = 'admin/Administration/AdministrationController/saveUser';
$route['edit-user'] = 'admin/Administration/AdministrationController/editUser';

$route['patients'] = 'admin/Administration/AdministrationController/patientList';
$route['new-patient'] = 'admin/Administration/AdministrationController/newPatient';
$route['save-patient'] = 'admin/Administration/AdministrationController/savePatient';
$route['edit-patient'] = 'admin/Administration/AdministrationController/editPatient';

$route['specialty'] = 'admin/Administration/AdministrationController/specialtyList';
$route['new-specialty'] = 'admin/Administration/AdministrationController/newSpecialty';
$route['save-specialty'] = 'admin/Administration/AdministrationController/saveSpecialty';
$route['edit-specialty'] = 'admin/Administration/AdministrationController/editSpecialty';

$route['chat'] = 'admin/Chat/ChatController';

$route['thank-you'] = 'admin/Appointment/AppointmentController/thankYou';


$route['404_override'] = 'admin/CommenController/page_not_found';
$route['translate_uri_dashes'] = FALSE;













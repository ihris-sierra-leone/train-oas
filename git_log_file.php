Fast-forward
 .idea/workspace.xml                                                   | 732 +++++++++++++++++++++++------------------------------
 ZTL_OAS.sql                                                           | 212 ++++++----------
 application/config/config.php                                         |   2 +-
 application/config/constants.php                                      |  18 +-
 application/config/constants_old.php                                  |  96 -------
 application/config/routes.php                                         |   6 +-
 application/controllers/Admin.php                                     |  12 +-
 application/controllers/Applicant.php                                 | 236 ++++++++++--------
 application/controllers/Auth.php                                      |  26 +-
 application/controllers/Home.php                                      |  21 +-
 application/controllers/Panel.php                                     | 151 ++++++++++-
 application/controllers/Popup.php                                     | 137 +++++++++-
 application/controllers/Report.php                                    |  36 +--
 application/controllers/Response.php                                  | 305 ++++++++++++++++-------
 application/controllers/Setting.php                                   |  30 +--
 application/controllers/Simsdata.php                                  | 166 ++++++++++--
 application/controllers/include/eligibility.php                       |  11 +-
 application/controllers/report/print_application.php                  |   2 +-
 application/helpers/common_helper.php                                 |   9 +-
 application/helpers/simsonline_helper.php                             |  14 +-+
 application/libraries/Ion_auth.php                                    |  16 +-
 application/libraries/Mail.php                                        |   4 +-
 application/models/Applicant_model.php                                | 197 +++++++++------
 application/models/Common_model.php                                   |  45 +++-
 application/models/Setting_model.php                                  |  13 +-
 application/views/applicant/applicant_attachment.php                  |   4 +
 application/views/applicant/applicant_education.php                   | 887 ++++++++++++++++++++++++++++++++++++-----------------------------
 application/views/applicant/applicant_payment.php                     |  11 +-
 application/views/applicant/applicant_submission.php                  |  12 +-
 application/views/applicant/basic_info.php                            |  19 +-
 application/views/applicant/choose_programme.php                      |  16 +-
 application/views/applicant/experience/intership.php                  |   6 +-
 application/views/applicant/home.php                                  |   3 -
 application/views/auth/forgot_password.php                            |   6 +-
 application/views/auth/login.php                                      |   2 +-
 application/views/auth/reset_password.php                             |   6 +-
 application/views/home/application_start.php                          |  58 +++--
 application/views/home/registration_start.php                         | 171 ++++++++-----
 application/views/include/panel_menu.php                              |   4 +-
 application/views/panel/applicant_list.php                            |   2 +-
 application/views/panel/applicant_selection.php                       |  21 +-
 application/views/panel/collection.php                                |   5 +-
 application/views/panel/manage_criteria.php                           |  16 ++
 application/views/panel/popup_applicant_info.php                      |   2 +-
 application/views/panel/programme_setting_selection.php               | 111 +++++++++
 application/views/panel/selection_criteria.php                        |  77 ++++++
 application/views/panel/set_criteria_rules.php                        |  35 ++-
 application/views/panel/short_listed.php                              |  43 ++--
 application/views/public_template.php                                 |  18 ++
 application/views/setting/manage_subject.php                          |   4 +-
 application/views/simsdata/add_programme.php                          |  39 ++-
 application/views/simsdata/department_list.php                        |  16 +-
 application/views/simsdata/manage_department.php                      |  76 ++++++
 application/views/simsdata/manage_school.php                          |  60 +++++
 application/views/simsdata/programme_list.php                         |  13 +-
 application/views/simsdata/school_list.php                            |  12 +-
 db_updates/update.sql                                                 |   2 +
 system/libraries/Email.php                                            |   6 +-
 uploads/attachment/14971754734751.pdf                                 | Bin 149082 -> 0 bytes
 uploads/attachment/14974224847154.jpg                                 | Bin 13410 -> 0 bytes
 uploads/attachment/14974283942597.jpg                                 | Bin 13410 -> 0 bytes
 uploads/attachment/14974300504264.pdf                                 | Bin 20733 -> 0 bytes
 uploads/attachment/14974300593342.pdf                                 | Bin 20675 -> 0 bytes
 uploads/attachment/14974321269837.pdf                                 | Bin 20675 -> 0 bytes
 uploads/attachment/14981185904868.pdf                                 | Bin 1147647 -> 0 bytes
 uploads/attachment/14981186670009.pdf                                 | Bin 7046 -> 0 bytes
 uploads/attachment/14981187153033.pdf                                 | Bin 33002 -> 0 bytes
 uploads/attachment/14984015492167.pdf                                 | Bin 1147647 -> 0 bytes
 uploads/attachment/14993496729704.pdf                                 | Bin 71278 -> 0 bytes
 uploads/{profile/14981182186643.jpg => attachment/15006370351009.jpg} | Bin
 uploads/profile/14971658494793.jpg                                    | Bin 55869 -> 0 bytes
 uploads/profile/14973494965119.jpg                                    | Bin 2012 -> 0 bytes
 uploads/profile/14974192352178.jpg                                    | Bin 13410 -> 0 bytes
 uploads/profile/14974295725307.jpg                                    | Bin 38381 -> 0 bytes
 uploads/profile/14993490797435.png                                    | Bin 188591 -> 0 bytes
 uploads/profile/14993507296758.jpg                                    | Bin 6075 -> 0 bytes
 uploads/profile/{14984009181341.jpg => 15006365838808.jpg}            | Bin
 77 files changed, 2583 insertions(+), 1677 deletions(-)
 mode change 100644 => 100755 application/config/config.php
 delete mode 100755 application/config/constants_old.php
 create mode 100644 application/views/panel/programme_setting_selection.php
 create mode 100644 application/views/panel/selection_criteria.php
 create mode 100644 application/views/simsdata/manage_department.php
 create mode 100644 application/views/simsdata/manage_school.php
 create mode 100644 db_updates/update.sql

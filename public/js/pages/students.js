!function(t){var e={};function a(n){if(e[n])return e[n].exports;var s=e[n]={i:n,l:!1,exports:{}};return t[n].call(s.exports,s,s.exports,a),s.l=!0,s.exports}a.m=t,a.c=e,a.d=function(t,e,n){a.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:n})},a.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(e,"a",e),e},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="/",a(a.s=9)}({9:function(t,e,a){t.exports=a("J48X")},J48X:function(t,e){$(document).ready(function(){var t,e="Makati",a="",n="",s="",r=$("#month_select").val(),o="Branch",l=!0;function c(){$("li.branch_pick, li.status_pick, li.result_pick, \n        li.language_pick, li.all_pick, li.ssv_pick, li.ssv_backout_pick").addClass("disabled").css("cursor","not-allowed"),$("a.branch_pick, a.status_pick, a.result_pick,\n        a.language_pick, a.all_pick, a.ssv_pick, a.ssv_backout_pick").addClass("disabled").css("pointer-events","none"),$(".switch, .refresh_table").attr("disabled",!0)}function i(){$("li.branch_pick, li.status_pick, li.result_pick, \n        li.language_pick, li.all_pick, li.ssv_pick, li.ssv_backout_pick").removeClass("disabled").css("cursor","pointer"),$("a.branch_pick, a.status_pick, a.result_pick,\n        a.language_pick, a.all_pick, a.ssv_pick, a.ssv_backout_pick").removeClass("disabled").css("pointer-events","auto"),$(".switch, .refresh_table").attr("disabled",!1)}function d(){switch(c(),"Branch"==o||"Status"==o||"Result"==o?(x(),$(".month_select").show(),$("#month_select").next(".select2-container").show(),$(".select_description").text("Departure:")):"Language"==o?(x(),$(".month_select").hide(),$("#month_select").next(".select2-container").hide(),$(".select_description").text("Year:")):"All"==o?($(".year_select").hide(),$("#year_select").next(".select2-container").hide(),$(".month_select").hide(),$("#month_select").next(".select2-container").hide(),$(".select_description").text("")):"SSV"!=o&&"SSV Backout"!=o||(x(),$(".month_select").hide(),$("#month_select").next(".select2-container").hide(),$(".select_description").text("Year:")),o){case"Branch":t=$("#year_select").val(),r=$("#month_select").val(),students_branch=$("#students_branch").DataTable({stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},initComplete:function(t,e){i()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,ajax:{url:"/student_branch",data:{current_branch:e,departure_year:t,departure_month:r}},columnDefs:m,columns:u,order:[[3,"asc"]]});break;case"Status":t=$("#year_select").val(),r=$("#month_select").val(),students_status=$("#students_status").DataTable({stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},initComplete:function(t,e){i()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,responsive:!0,ajax:{url:"/student_status",data:{current_status:a,departure_year:t,departure_month:r}},columnDefs:h,columns:g,order:[[4,"asc"]]});break;case"Result":t=$("#year_select").val(),r=$("#month_select").val(),students_result=$("#students_result").DataTable({stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},initComplete:function(t,e){i()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,responsive:!0,ajax:{url:"/student_result",data:{current_result:n,departure_year:t,departure_month:r}},columnDefs:p,columns:_,order:[[3,"asc"]]});break;case"Language":t=$("#year_select").val(),language_students=$("#language_students").DataTable({stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},initComplete:function(t,e){i()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,responsive:!0,ajax:{url:"/language_student",data:{departure_year:t}},columnDefs:b,columns:f});break;case"SSV":t=$("#year_select").val(),ssv_students=$("#ssv_students").DataTable({stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},initComplete:function(t,e){i()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,responsive:!0,ajax:{url:"/ssv_student",data:{departure_year:t,current_ssv:s}},columnDefs:S,columns:w,order:[[4,"asc"]]});break;case"SSV Backout":t=$("#year_select").val(),ssv_backout=$("#ssv_backout").DataTable({stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},initComplete:function(t,e){i()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,responsive:!0,ajax:{url:"/ssv_student",data:{departure_year:t,current_ssv:s}},columnDefs:C,columns:y,order:[[4,"asc"]]});break;case"All":all_students=$("#all_students").DataTable({stateSave:!0,stateSaveCallback:function(t,e){localStorage.setItem("DataTables_"+t.sInstance,JSON.stringify(e))},stateLoadCallback:function(t){return JSON.parse(localStorage.getItem("DataTables_"+t.sInstance))},stateLoadParams:function(t,e){e.order&&delete e.order},initComplete:function(t,e){i()},processing:!0,destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,responsive:!0,ajax:{url:"/all_student",data:{}},columnDefs:k,columns:v})}}$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/get_current_year",method:"get",dataType:"json",success:function(e){$("#year_select").val(e).trigger("change"),t=$("#year_select").val()}}),$(".datepicker").datepicker({format:"yyyy-mm-dd",forceParse:!1}),$(".select2").select2(),$("body").tooltip({selector:'[data-toggle="tooltip"]',trigger:"hover"}),$('[data-toggle="tooltip"]').click(function(){$('[data-toggle="tooltip"]').tooltip("hide")}),$("#student_modal").on("shown.bs.modal",function(){$("#fname").focus()}),$("#student_modal").on("hidden.bs.modal",function(t){$("#student_form :input.required").each(function(){this.style.setProperty("border-color","green","important")}),$("#language_student_form :input.required").each(function(){this.style.setProperty("border-color","green","important")}),$(this).find("input,textarea,select").val("").end(),$(".select2").trigger("change.select2"),l=!0}),$("input, select").attr("autocomplete","off"),$(".refresh_table").on("click",function(){d()}),$(document).on("shown.bs.tab",'a[data-toggle="tab"]',function(t){1==l&&(d(),$.fn.dataTable.tables({visible:!0,api:!0}).columns.adjust())});var u=[{data:"name",name:"name"},{data:"contact",name:"contact"},{data:"program.name",name:"program"},{data:"school.name",name:"school"},{data:"benefactor.name",name:"benefactor"},{data:"gender",name:"gender"},{data:"age",name:"age"},{data:"course.name",name:"course"},{data:"email",name:"email"},{data:"date_of_signup",name:"date_of_signup"},{data:"referral.fname",name:"referral"},{data:"status",name:"status"},{data:"coe_status",name:"coe_status"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],m=[{width:230,targets:0},{width:90,targets:1},{width:130,targets:2},{width:130,targets:3},{width:130,targets:4},{width:60,targets:5},{width:45,targets:6},{width:200,targets:7},{width:200,targets:8},{width:120,targets:9},{width:120,targets:10},{width:100,targets:11},{width:100,targets:12},{width:250,targets:13},{width:150,targets:14},{defaultContent:"",targets:"_all"}],g=[{data:"name",name:"name"},{data:"branch.name",name:"branch"},{data:"contact",name:"contact"},{data:"program.name",name:"program"},{data:"school.name",name:"school"},{data:"benefactor.name",name:"benefactor"},{data:"gender",name:"gender"},{data:"age",name:"age"},{data:"course.name",name:"course"},{data:"email",name:"email"},{data:"date_of_signup",name:"date_of_signup"},{data:"referral.fname",name:"referral"},{data:"status",name:"status"},{data:"coe_status",name:"coe_status"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],h=[{width:230,targets:0},{width:70,targets:1},{width:90,targets:2},{width:130,targets:3},{width:130,targets:4},{width:130,targets:5},{width:60,targets:6},{width:45,targets:7},{width:200,targets:8},{width:200,targets:9},{width:120,targets:10},{width:120,targets:11},{width:100,targets:12},{width:100,targets:13},{width:250,targets:14},{width:150,targets:15},{defaultContent:"",targets:"_all"}],_=[{data:"name",name:"name"},{data:"branch.name",name:"branch"},{data:"program.name",name:"program"},{data:"school.name",name:"school"},{data:"status",name:"status"},{data:"coe_status",name:"coe_status"},{data:"referral.fname",name:"referral"},{data:"action",orderable:!1,searchable:!1}],p=[{width:230,targets:0},{width:70,targets:1},{width:90,targets:2},{width:130,targets:3},{width:100,targets:4},{width:100,targets:5},{width:120,targets:6},{width:150,targets:7},{defaultContent:"",targets:"_all"}],f=[{data:"name",name:"name"},{data:"branch.name",name:"branch"},{data:"contact",name:"contact"},{data:"gender",name:"gender"},{data:"age",name:"age"},{data:"course.name",name:"course"},{data:"email",name:"email"},{data:"referral.fname",name:"referral"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],b=[{width:230,targets:0},{width:70,targets:1},{width:90,targets:2},{width:60,targets:3},{width:45,targets:4},{width:200,targets:5},{width:200,targets:6},{width:120,targets:7},{width:250,targets:8},{width:150,targets:9},{defaultContent:"",targets:"_all"}],v=g,k=h,w=[{data:"name",name:"name"},{data:"contact",name:"contact"},{data:"gender",name:"gender"},{data:"age",name:"age"},{data:"program.name",name:"program"},{data:"benefactor.name",name:"benefactor"},{data:"course.name",name:"course"},{data:"email",name:"email"},{data:"referral.fname",name:"referral"},{data:"remarks",name:"remarks"},{data:"action",orderable:!1,searchable:!1}],S=[{width:230,targets:0},{width:90,targets:1},{width:60,targets:2},{width:45,targets:3},{width:130,targets:4},{width:130,targets:5},{width:200,targets:6},{width:200,targets:7},{width:120,targets:8},{width:250,targets:9},{width:150,targets:10},{defaultContent:"",targets:"_all"}],y=w,C=S;function x(){$(".year_select").show(),$("#year_select").next(".select2-container").show()}function T(t){var e=new Date,a=e.getDate(),n=e.getMonth()+1,s=e.getFullYear();e=s+"-"+n+"-"+a,birth_array=t.split("-");var r=s-parseInt(birth_array[0]);return n==parseInt(birth_array[1])?a<birth_array[2]&&r--:n<parseInt(birth_array[1])&&r--,r}function j(t){$.ajax({url:"/view_profile_student/"+t,method:"get",dataType:"JSON",success:function(t){$("#p_picture").attr("src","./storage/img/student/"+t.picture),t.mname?$("#p_stud_name").text(t.lname+", "+t.fname+" "+t.mname):$("#p_stud_name").text(t.lname+", "+t.fname),t.program?"Language Only"!=t.program.name&&"SSV (Careworker)"!=t.program.name&&"SSV (Hospitality)"!=t.program.name?$("#p_departure").text(t.departure_year.name+" "+t.departure_month.name):$("#p_departure").text("N/A"):$("#p_departure").text(t.departure_year.name+" "+t.departure_month.name),$("#p_contact").text(t.contact),$("#p_program").text(t.program?t.program.name:"-"),$("#p_school").text(t.school?t.school.name:"-"),$("#p_benefactor").text(t.benefactor?t.benefactor.name:"-"),$("#p_birthdate").text(t.birthdate+" ("+t.age+")"),$("#p_gender").text(t.gender),$("#p_referral").text(t.referral.fname),$("#p_sign_up").text(t.date_of_signup),$("#p_medical").text(t.date_of_medical?t.date_of_medical:"-"),$("#p_completion").text(t.date_of_completion?t.date_of_completion:"-"),$("#p_branch").text(t.branch.name),$("#p_status").text(t.status),$("#p_coe_status").text(t.coe_status),$("#p_email").text(t.email),$("#p_course").text(t.course.name),$("#p_address").text(t.address),$("#p_remarks").text(t.remarks?t.remarks:"-")}})}$(".add_student").on("click",function(){}),$("#birthdate").on("change",function(){var t=T($(this).val());$("#age").val(t)}),$("#l_birthdate").on("change",function(){var t=T($(this).val());$("#l_age").val(t)}),$("#s_birthdate").on("change",function(){var t=T($(this).val());$("#s_age").val(t)}),$(".switch").on("click",function(){"SSV"==$("#switch_name").text()?($("#switch_name").text("Student"),$(".branch_pick, .status_pick, .result_pick, .language_pick").hide(),$(".ssv_pick, .ssv_backout_pick").show(),$("#student_list_tab #ssv_first").click()):"Student"==$("#switch_name").text()&&($("#switch_name").text("SSV"),$(".branch_pick, .status_pick, .result_pick, .language_pick").show(),$(".ssv_pick, .ssv_backout_pick").hide(),$("#student_list_tab #student_first").click()),c()}),$(".branch_pick").on("click",function(){$(this).hasClass("disabled")||(o="Branch",e=$(this).text())}),$(".status_pick").on("click",function(){$(this).hasClass("disabled")||(o="Status",a=$(this).text())}),$(".result_pick").on("click",function(){$(this).hasClass("disabled")||(o="Result",n=$(this).text())}),$(".language_pick").on("click",function(){$(this).hasClass("disabled")||(o="Language")}),$(".all_pick").on("click",function(){$(this).hasClass("disabled")||(o="All")}),$(".ssv_pick").on("click",function(){$(this).hasClass("disabled")||(o="SSV",s=$(this).text())}),$(".ssv_backout_pick").on("click",function(){$(this).hasClass("disabled")||(o="SSV Backout",s=$(this).text())}),$(document).on("change","#year_select, #month_select",function(){d()}),$(document).on("submit","#student_form",function(t){t.preventDefault();var e=$(".save_student"),a=document.getElementsByClassName("save_student")[0];a.disabled=!0,e.html("SAVING...");var n=new FormData($(this)[0]);$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_student",method:"POST",data:n,processData:!1,contentType:!1,success:function(t){if(0==t)return swal("Error!","File/Image format must only be .jpg | .png | .jpeg","error"),a.disabled=!1,void e.html("SAVE CHANGES");$("#student_modal").modal("hide"),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),a.disabled=!1,e.html("SAVE CHANGES"),d(),j(t)},error:function(t){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,e.html("SAVE CHANGES")}})}),$(document).on("submit","#language_student_form",function(t){t.preventDefault();var e=$(".save_language_student"),a=document.getElementsByClassName("save_language_student")[0];a.disabled=!0,e.html("SAVING...");var n=new FormData($(this)[0]);$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_language_student",method:"POST",data:n,processData:!1,contentType:!1,success:function(t){if(0==t)return swal("Error!","File/Image format must only be .jpg | .png | .jpeg","error"),a.disabled=!1,void e.html("SAVE CHANGES");$("#student_modal").modal("hide"),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),a.disabled=!1,e.html("SAVE CHANGES"),d(),j(t)},error:function(t){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,e.html("SAVE CHANGES")}})}),$(document).on("submit","#ssv_student_form",function(t){t.preventDefault();var e=$(".save_ssv_student"),a=document.getElementsByClassName("save_ssv_student")[0];a.disabled=!0,e.html("SAVING...");var n=new FormData($(this)[0]);$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_ssv_student",method:"POST",data:n,processData:!1,contentType:!1,success:function(t){if(0==t)return swal("Error!","File/Image format must only be .jpg | .png | .jpeg","error"),a.disabled=!1,void e.html("SAVE CHANGES");$("#student_modal").modal("hide"),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),a.disabled=!1,e.html("SAVE CHANGES"),d(),j(t)},error:function(t){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,e.html("SAVE CHANGES")}})}),$(".add_student").on("click",function(){l=!1,$("#add_edit").val("add"),$("#l_add_edit").val("add"),$("#s_add_edit").val("add"),$("#student_type_tab a:first").tab("show"),$("#student_modal").modal("toggle"),$("#student_modal").modal("show")}),$(document).on("click",".edit_student",function(){l=!1,$('#student_type_tab a[href="#student_form"]').tab("show");var t=$(this).attr("id");$.ajax({url:"/get_student",method:"get",data:{id:t},dataType:"json",success:function(t){$("#add_edit").val("edit"),$("#id").val(t.id),$("#fname").val(t.fname),$("#mname").val(t.mname),$("#lname").val(t.lname),$("#birthdate").val(t.birthdate),$("#age").val(t.age),$("#contact").val(t.contact),t.program&&$("#program").val(t.program.id).trigger("change"),t.school&&$("#school").val(t.school.id).trigger("change"),t.benefactor&&$("#benefactor").val(t.benefactor.id).trigger("change"),$("#address").val(t.address),$("#email").val(t.email),$("#sign_up").val(t.date_of_signup),$("#medical").val(t.date_of_medical),$("#completion").val(t.date_of_completion),$("#referral").val(t.referral.id).trigger("change"),$("#gender").val(t.gender).trigger("change"),$("#branch").val(t.branch.id).trigger("change"),$("#course").val(t.course.id).trigger("change"),$("#year").val(t.departure_year.id).trigger("change"),$("#month").val(t.departure_month.id).trigger("change"),$("#remarks").val(t.remarks),$("#student_modal").modal("toggle"),$("#student_modal").modal("show")}})}),$(document).on("click",".edit_language_student",function(){l=!1,$('#student_type_tab a[href="#language_student_form"]').tab("show");var t=$(this).attr("id");$.ajax({url:"/get_student",method:"get",data:{id:t},dataType:"json",success:function(t){$("#l_add_edit").val("edit"),$("#l_id").val(t.id),$("#l_fname").val(t.fname),$("#l_mname").val(t.mname),$("#l_lname").val(t.lname),$("#l_birthdate").val(t.birthdate),$("#l_age").val(t.age),$("#l_contact").val(t.contact),$("#l_address").val(t.address),$("#l_email").val(t.email),$("#l_referral").val(t.referral.id).trigger("change"),$("#l_gender").val(t.gender).trigger("change"),$("#l_branch").val(t.branch.id).trigger("change"),$("#l_course").val(t.course.id).trigger("change"),$("#l_year").val(t.departure_year.id).trigger("change"),$("#l_remarks").val(t.remarks),$("#student_modal").modal("toggle"),$("#student_modal").modal("show")}})}),$(document).on("click",".edit_ssv_student",function(){l=!1,$('#student_type_tab a[href="#ssv_student_form"]').tab("show");var t=$(this).attr("id");$.ajax({url:"/get_student",method:"get",data:{id:t},dataType:"json",success:function(t){$("#s_add_edit").val("edit"),$("#s_id").val(t.id),$("#s_fname").val(t.fname),$("#s_mname").val(t.mname),$("#s_lname").val(t.lname),$("#s_birthdate").val(t.birthdate),$("#s_age").val(t.age),$("#s_contact").val(t.contact),t.program&&$("#s_program").val(t.program.id).trigger("change"),t.benefactor&&$("#s_benefactor").val(t.benefactor.id).trigger("change"),$("#s_address").val(t.address),$("#s_email").val(t.email),$("#s_referral").val(t.referral.id).trigger("change"),$("#s_gender").val(t.gender).trigger("change"),$("#s_branch").val(t.branch.id).trigger("change"),$("#s_course").val(t.course.id).trigger("change"),$("#s_year").val(t.departure_year.id).trigger("change"),$("#s_remarks").val(t.remarks),$("#student_modal").modal("toggle"),$("#student_modal").modal("show")}})}),$(document).on("click",".delete_student",function(){var t=$(this).attr("id");swal({title:"Are you sure?",text:"You are about to delete a student. This may affect multiple rows",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(e){e.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_student",method:"get",data:{id:t},type:"json",success:function(t){notif("Success!","This Student has been Deleted","success","glyphicon-ok"),d()}})})}),$(document).on("click",".final_student",function(){var t=$(this).attr("id");swal({title:"Go for Final School?",text:"This Student will be in Final School",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(e){e.value&&$.ajax({url:"/final_student",method:"get",data:{id:t},dataType:"text",success:function(t){notif("Success!","This Student is now in Final School!","success","glyphicon-ok"),j(t),d()}})})}),$(document).on("click",".backout_student",function(){var t=$(this).attr("id");swal({title:"Student will backout?",text:"This Student will be transferred to list of backouts",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(e){e.value&&$.ajax({url:"/backout_student",method:"get",data:{id:t},dataType:"text",success:function(t){notif("This Student has backed out!","","warning","glyphicon-warning-sign"),j(t),d()}})})}),$(document).on("click",".continue_student",function(){var t=$(this).attr("id");swal({title:"Student will apply again?",text:"This Student will be transferred to list of Active Students",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(e){e.value&&$.ajax({url:"/continue_student",method:"get",data:{id:t},dataType:"text",success:function(t){"Final School"==a?notif("Success!","This Student is out of Final School!","success","glyphicon-ok"):notif("Success!","This Student is now active again!","success","glyphicon-ok"),j(t),d()}})})}),$(document).on("click",".approve_student",function(){var t=$(this).attr("id");swal({title:"Student COE Approved?",text:"Confirm that this student's COE is approved?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(e){e.value&&$.ajax({url:"/approve_student",method:"get",data:{id:t},dataType:"text",success:function(t){notif("Congratulations!","Student COE Approved!","success","glyphicon-ok"),j(t),d()}})})}),$(document).on("click",".deny_student",function(){var t=$(this).attr("id");swal({title:"Student COE Denied?",text:"Confirm that this student's COE is denied?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(e){e.value&&$.ajax({url:"/deny_student",method:"get",data:{id:t},dataType:"text",success:function(t){notif("Student COE Denied","","warning","glyphicon-warning-sign"),j(t),d()}})})}),$(document).on("click",".cancel_student",function(){var t=$(this).attr("id");swal({title:"Student will Cancel?",text:"Confirm that this student decided to cancel?",type:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes!"}).then(function(e){e.value&&$.ajax({url:"/cancel_student",method:"get",data:{id:t},dataType:"text",success:function(t){notif("Student Cancelled","","warning","glyphicon-warning-sign"),j(t),d()}})})}),$(document).on("click",".view_profile",function(){j($(this).attr("id"))}),$("#course, #l_course, #s_course").select2({placeholder:"Select Course",ajax:{url:"/courseAll",dataType:"json",data:function(t){return{name:t.term,page:t.page}},processResults:function(t){return{results:t.results}}}}),$("#program").select2({allowClear:!0,placeholder:"Select Program",ajax:{url:"/programAll",dataType:"json",data:function(t){return{name:t.term,page:t.page}},processResults:function(t){return{results:t.results}}}}),$("#s_program").select2({allowClear:!0,placeholder:"Select Program",ajax:{url:"/programSSV",dataType:"json",data:function(t){return{name:t.term,page:t.page}},processResults:function(t){return{results:t.results}}}}),$("#school").select2({allowClear:!0,placeholder:"Select School",ajax:{url:"/schoolAll",dataType:"json",data:function(t){return{name:t.term,page:t.page}},processResults:function(t){return{results:t.results}}}}),$("#benefactor, #s_benefactor").select2({allowClear:!0,placeholder:"Select Benefactor",ajax:{url:"/benefactorAll",dataType:"json",data:function(t){return{name:t.term,page:t.page}},processResults:function(t){return{results:t.results}}}})})}});
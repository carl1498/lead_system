!function(e){var t={};function a(o){if(t[o])return t[o].exports;var n=t[o]={i:o,l:!1,exports:{}};return e[o].call(n.exports,n,n.exports,a),n.l=!0,n.exports}a.m=e,a.c=t,a.d=function(e,t,o){a.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:o})},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/",a(a.s=3)}({3:function(e,t,a){e.exports=a("esuD")},esuD:function(e,t){$(document).ready(function(){var e="All",t=$("#status_select").val(),a=!0;function o(){$("li.tab_pick").removeClass("disabled").css("cursor","pointer"),$("a.tab_pick").removeClass("disabled").css("pointer-events","auto"),$(".refresh_table").attr("disabled",!1)}function n(){$("li.tab_pick").addClass("disabled").css("cursor","not-allowed"),$("a.tab_pick").addClass("disabled").css("pointer-events","none"),$(".refresh_table").attr("disabled",!0),buttons_format=[{extend:"excelHtml5",title:"Employee - Branch("+e+") Status("+t+")",exportOptions:{columns:":visible"}},"colvis"],"All"==e?$("#employees_all").DataTable({initComplete:function(e,t){o()},dom:"Bflrtip",destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,buttons:buttons_format,responsive:!0,ajax:"/employee_all/"+t,columns:s}):$("#employees_branch").DataTable({initComplete:function(e,t){o()},dom:"Bflrtip",destroy:!0,scrollX:!0,scrollCollapse:!0,fixedColumns:!0,buttons:buttons_format,responsive:!0,ajax:{url:"/employee_branch",data:{current_branch:e,employee_status:t}},columns:l})}$(function(){!function(){console.log("mao ni"),console.log($(window).width());var e=$("#scroller-anchor"),t=$("#box-primary-fixed"),a=function(){var a=$(window).scrollTop(),o=e.offset().top;$(window).width()>991&&(a>o?t.css({position:"fixed",top:"10px"}):t.css({position:"relative",top:""}))};$(window).scroll(a),a()}()}),$(".datepicker").datepicker({format:"yyyy-mm-dd",forceParse:!1}),$("body").tooltip({selector:'[data-toggle="tooltip"]',trigger:"hover"}),$('[data-toggle="tooltip"]').click(function(){$('[data-toggle="tooltip"]').tooltip("hide")}),$(".select2").select2(),$("#employee_modal").on("shown.bs.modal",function(){$("#fname").focus()}),$("#employee_modal").on("hidden.bs.modal",function(e){$("#employee_form :input.required").each(function(){this.style.setProperty("border-color","green","important")}),$(this).find("input,textarea,select").val("").end(),$(".select2").trigger("change.select2"),a=!0}),$("#account_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end(),a=!0}),$("#emergency_modal, #spouse_modal, #child_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end(),setTimeout(function(){$("#employee_family_modal").modal("show")},500)}),$("#prev_employment_modal, #educational_background_modal").on("hidden.bs.modal",function(e){$(this).find("input,textarea,select").val("").end(),$(".select2").trigger("change.select2"),setTimeout(function(){$("#employee_history_modal").modal("show")},500)}),$("#employee_history_modal, #employee_family_modal").on("hidden.bs.modal",function(e){a=!0}),$("#resign_modal, #rehire_modal").on("hidden.bs.modal",function(e){$(this).find("#resignation_date, #rehiring_date").val("").end(),a=!0}),$("input, select").attr("autocomplete","off"),$(".refresh_table").on("click",function(){n()}),$(document).on("shown.bs.tab",'a[data-toggle="tab"]',function(e){1==a&&(n(),$.fn.dataTable.tables({visible:!0,api:!0}).columns.adjust())});var l=[{data:"name",name:"name"},{data:"contact_personal",name:"contact_personal"},{data:"contact_business",name:"contact_business"},{data:"birthdate",name:"birthdate"},{data:"gender",name:"gender"},{data:"email",name:"email"},{data:"role.name",name:"role.name"},{data:"hired_date",name:"hired_date"},{data:"employment_status",name:"status"},{data:"action",orderable:!1,searchable:!1}],s=[{data:"name",name:"name"},{data:"branch.name",name:"branch"},{data:"contact_personal",name:"contact_personal"},{data:"contact_business",name:"contact_business"},{data:"birthdate",name:"birthdate"},{data:"gender",name:"gender"},{data:"email",name:"email"},{data:"role.name",name:"role.name"},{data:"hired_date",name:"hired_date"},{data:"employment_status",name:"status"},{data:"action",orderable:!1,searchable:!1}];function r(e){$("#employment_history_table").DataTable({paging:!1,ordering:!1,info:!1,searching:!1,destroy:!0,ajax:"/view_employment_history/"+e,columns:[{data:"hired_date",name:"hired_date"},{data:"until",name:"until"},{data:"months",name:"months"},{data:"action",orderable:!1,searchable:!1}]}),$("#prev_employment_history_table").DataTable({paging:!1,ordering:!1,info:!1,searching:!1,destroy:!0,ajax:"/view_prev_employment_history/"+e,columns:[{data:"company",name:"company"},{data:"address",name:"address"},{data:"hired_date",name:"hired_date"},{data:"until",name:"until"},{data:"months",name:"months"},{data:"salary",name:"salary"},{data:"designation",name:"designation"},{data:"employment_type",name:"employment_type"},{data:"action",orderable:!1,searchable:!1}]}),$("#educational_background_table").DataTable({paging:!1,ordering:!1,info:!1,searching:!1,destroy:!0,ajax:"/view_educational_background/"+e,columns:[{data:"school",name:"school"},{data:"start",name:"start"},{data:"end",name:"end"},{data:"course.name",name:"course"},{data:"level",name:"level"},{data:"awards",name:"awards"},{data:"action",orderable:!1,searchable:!1}]})}function d(e){$("#employee_emergency_table").DataTable({paging:!1,ordering:!1,info:!1,searching:!1,destroy:!0,autoWidth:!1,ajax:"/view_employee_emergency/"+e,columns:[{data:"name",name:"name"},{data:"contact",name:"contact"},{data:"relationship",name:"relationship"},{data:"action",orderable:!1,searchable:!1}]}),$("#employee_spouse_table").DataTable({paging:!1,ordering:!1,info:!1,searching:!1,destroy:!0,autoWidth:!1,ajax:"/view_employee_spouse/"+e,columns:[{data:"name",name:"name"},{data:"contact",name:"contact"},{data:"birthdate",name:"birthdate"},{data:"action",orderable:!1,searchable:!1}]}),$("#employee_child_table").DataTable({paging:!1,ordering:!1,info:!1,searching:!1,destroy:!0,autoWidth:!1,ajax:"/view_employee_child/"+e,columns:[{data:"name",name:"name"},{data:"gender",name:"gender"},{data:"birthdate",name:"birthdate"},{data:"action",orderable:!1,searchable:!1}]})}function m(e){$.ajax({url:"/view_profile_employee/"+e,method:"get",dataType:"json",success:function(e){$("#p_picture").attr("src","./storage/img/employee/"+e.picture),e.mname?$("#p_emp_name").text(e.lname+", "+e.fname+" "+e.mname):$("#p_emp_name").text(e.lname+", "+e.fname),$("#p_position").text(e.role.name),$("#p_business").text(e.contact_business?e.contact_business:"-"),$("#p_personal").text(e.contact_personal?e.contact_personal:"-"),$("#p_email").text(e.email),$("#p_birthdate").text(e.birthdate+" ("+e.age+")"),$("#p_gender").text(e.gender),$("#p_branch").text(e.branch.name),$("#p_status").text(e.employment_status+" "+e.leaves),$("#p_probationary").text(e.probationary),$("#p_hired").text(e.current_employment_status.hired_date?e.current_employment_status.hired_date:"-");var t=e.months?e.months:"";$("#p_until").text(e.current_employment_status.until?e.current_employment_status.until+" ("+t+")":"Present ("+e.months+")"),$("#p_sss").text(e.benefits[0].id_number?e.benefits[0].id_number:"-"),$("#p_pagibig").text(e.benefits[1].id_number?e.benefits[1].id_number:"-"),$("#p_philhealth").text(e.benefits[2].id_number?e.benefits[2].id_number:"-"),$("#p_tin").text(e.benefits[3].id_number?e.benefits[3].id_number:"-");for(var a=0!=e.employee_emergency.length?"":"-",o=0;o<e.employee_emergency.length;o++)i=e.employee_emergency[o],a+=i.fname+" "+i.lname+"<br>"+i.relationship+"<br>"+i.contact,o!=e.employee_emergency.length-1&&(a+="<br><br>");$("#p_emergency").html(a)}})}n(),$(document).on("change","#status_select",function(){t=$(this).val(),n()}),$(".tab_pick").on("click",function(){$(this).hasClass("disabled")||(e=$(this).text())}),$(document).on("submit","#employee_form",function(e){e.preventDefault();var t=$(".save_employee"),a=document.getElementsByClassName("save_employee")[0];a.disabled=!0,t.html("SAVING...");var o=new FormData($(this)[0]);return $.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_employee",method:"POST",data:o,processData:!1,contentType:!1,success:function(e){if(0==e)return swal("Error!","File/Image format must only be .jpg | .png | .jpeg","error"),a.disabled=!1,void t.html("SAVE CHANGES");$("#employee_modal").modal("hide"),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok"),a.disabled=!1,t.html("SAVE CHANGES"),n(),m(e)},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}}),!1}),$(".add_employee").on("click",function(){a=!1,$("#add_edit").val("add"),$("#employee_modal").modal("show")}),$(document).on("click",".edit_employee",function(){a=!1;var e=$(this).attr("id");$.ajax({url:"/get_employee/"+e,method:"get",dataType:"json",success:function(e){$("#add_edit").val("edit"),$("#id").val(e.employee.id),$("#fname").val(e.employee.fname),$("#mname").val(e.employee.mname),$("#lname").val(e.employee.lname),$("#birthdate").val(e.employee.birthdate),$("#gender").val(e.employee.gender).trigger("change"),$("#personal_no").val(e.employee.contact_personal),$("#business_no").val(e.employee.contact_business),$("#email").val(e.employee.email),$("#address").val(e.employee.address),$("#branch").val(e.employee.branch_id).trigger("change"),$("#role").val(e.employee.role_id).trigger("change"),$("#salary").val(e.employee.salary),$("#hired").val(e.employment_history.hired_date),$("#sss").val(e.benefits[0].id_number),$("#pagibig").val(e.benefits[1].id_number),$("#philhealth").val(e.benefits[2].id_number),$("#tin").val(e.benefits[3].id_number),$("#employee_modal").modal("show")}})}),$(document).on("click",".delete_employee",function(){var e=$(this).attr("id");swal({title:"Are you sure?",text:"You are about to delete an employee. This may affect multiple rows",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_employee",method:"get",data:{id:e},type:"json",success:function(e){notif("Deleted!","This Employee has been Deleted","success","glyphicon-ok"),n()}})})}),$(document).on("click",".edit_account",function(){var e=$(this).attr("id");$.ajax({url:"/get_account/"+e,dataType:"json",success:function(e){$("#a_id").val(e.id),e.employee.mname?$("#emp_name").val(e.employee.lname+", "+e.employee.fname+" "+e.employee.mname):$("#emp_name").val(e.employee.lname+", "+e.employee.fname),$("#username").val(e.username),$("#account_modal").modal("show")}})}),$(document).on("submit","#account_form",function(e){e.preventDefault(),""==$("#password").val()||$("#password").val()==$("#confirm_password").val()?swal.fire({title:"Confirm User",text:"For security purposes, input your password again.",input:"password",inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Confirm",showLoaderOnConfirm:!0,preConfirm:function(e){$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/confirm_user",data:{password:e},method:"POST",success:function(e){0!=e?$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_account",data:$("#account_form").serialize(),method:"POST",dataType:"text",success:function(e){notif("Success!","User Data has been saved!","success","glyphicon-ok"),$("#account_modal").modal("hide"),n()}}):swal("Password Incorrect!","Please try again","error")}})}}):swal("Error!","Password and Confirm Password must be identical","error")}),$(document).on("click",".resign_employee",function(){var e=$(this).attr("id");$("#r_id").val(e),$("#resign_modal").modal("show")}),$(document).on("submit","#resign_form",function(e){e.preventDefault(),swal.fire({title:"Confirm User",text:"For security purposes, input your password again.",input:"password",inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Confirm",showLoaderOnConfirm:!0,preConfirm:function(e){$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/confirm_user",data:{password:e},method:"POST",success:function(e){0!=e?$.ajax({url:"/save_resign_employee",method:"POST",dataType:"text",data:$("#resign_form").serialize(),success:function(e){$("#resign_modal").modal("hide"),notif("Employee now resigned.","","info","glyphicon-info-sign"),$(".title_status").text("Resigned"),$(".resign_rehire").attr("data-original-title","Rehire").attr("id",e).removeClass("resign_employee").addClass("rehire_employee"),$(".resign_rehire i").removeClass("fa-sign-out-alt").addClass("fa-sign-in-alt"),r(e),n()}}):swal("Password Incorrect!","Please try again","error")}})}})}),$(document).on("click",".rehire_employee",function(){var e=$(this).attr("id");$("#rh_id").val(e),$("#rehire_modal").modal("show")}),$(document).on("submit","#rehire_form",function(e){e.preventDefault(),swal.fire({title:"Confirm User",text:"For security purposes, input your password again.",input:"password",inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Confirm",showLoaderOnConfirm:!0,preConfirm:function(e){$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/confirm_user",data:{password:e},method:"POST",success:function(e){0!=e?$.ajax({url:"/save_rehire_employee",method:"POST",dataType:"text",data:$("#rehire_form").serialize(),success:function(e){$("#rehire_modal").modal("hide"),notif("Success!","Employee now rehired","success","glyphicon-ok"),$(".title_status").text("Active"),$(".resign_rehire").attr("data-original-title","Resign").attr("id",e).removeClass("rehire_employee").addClass("resign_employee"),$(".resign_rehire i").removeClass("fa-sign-in-alt").addClass("fa-sign-out-alt"),r(e),n()}}):swal("Password Incorrect!","Please try again","error")}})}})}),$(document).on("click",".view_employee_profile",function(){m($(this).attr("id"))}),$(document).on("click",".history_employee",function(){var e=$(this).attr("id");$.ajax({url:"/get_employee/"+e,method:"get",dataType:"json",success:function(t){$("#employee_history_modal .title_name").text(t.employee.fname+" "+t.employee.lname),$("#employee_history_modal .title_status").text(t.employee.employment_status),$("#employee_history_modal .title_probationary").text(t.employee.probationary+" - Lead Employment History"),"Active"==t.employee.employment_status?($(".resign_rehire").attr({"data-original-title":"Resign",id:e}).removeClass("rehire_employee").addClass("resign_employee"),$(".resign_rehire i").removeClass("fa-sign-in-alt").addClass("fa-sign-out-alt")):($(".resign_rehire").attr({"data-original-title":"Rehire",id:e}).removeClass("resign_employee").addClass("rehire_employee"),$(".resign_rehire i").removeClass("fa-sign-out-alt").addClass("fa-sign-in-alt")),$(".add_employment_history, .add_educational").attr({id:e}),r(e)}}),$("#employee_history_modal").modal("show")}),$(document).on("click",".edit_employment_history",function(){var e=$(this).attr("id");$.ajax({url:"/get_employment_history/"+e,method:"get",dataType:"json",success:function(e){$("#eh_id").val(e.id),$("#edit_hired_date").val(e.hired_date),$("#edit_until").val(e.until)}}),$("#edit_employee_history_modal").modal("show")}),$(document).on("submit","#edit_employee_history_form",function(e){e.preventDefault(),$.ajax({url:"/save_employment_history",method:"POST",dataType:"text",data:$(this).serialize(),success:function(e){notif("Edit Success!","","success","glyphicon-ok"),$("#edit_employee_history_modal").modal("hide"),r(e),m(e),n()},error:function(e){swal("Error!","Something went wrong, try again.","error"),button.disabled=!1,input.html("SAVE CHANGES")}})}),$(document).on("click",".edit_employment_history",function(){var e=$(this).attr("id");$.ajax({url:"/get_employment_history/"+e,method:"get",dataType:"json",success:function(e){$("#eh_id").val(e.id),$("#edit_hired_date").val(e.hired_date),$("#edit_until").val(e.until)}}),$("#edit_employee_history_modal").modal("show")}),$(document).on("click",".add_employment_history",function(){var e=$(this).attr("id");$("#pe_emp_id").val(e),$("#pe_add_edit").val("add"),$("#employee_history_modal").modal("hide"),setTimeout(function(){$("#prev_employment_modal").modal("show")},500)}),$(document).on("submit","#prev_employment_form",function(e){e.preventDefault();var t=$(".save_prev_employment"),a=document.getElementsByClassName("save_prev_employment")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_prev_employment_history",method:"POST",data:$(this).serialize(),success:function(e){$("#prev_employment_modal").modal("hide"),setTimeout(function(){$("#employee_history_modal").modal("show")},500),a.disabled=!1,t.html("SAVE CHANGES"),r(e),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok")},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".edit_prev_employment_history",function(){var e=$(this).attr("id");$.ajax({url:"/get_prev_employment_history/"+e,method:"get",dataType:"json",success:function(e){$("#pe_add_edit").val("edit"),$("#pe_id").val(e.id),$("#pe_emp_id").val(e.emp_id),$("#pe_company").val(e.company),$("#pe_address").val(e.address),$("#pe_hired_date").val(e.hired_date),$("#pe_until").val(e.until),$("#pe_salary").val(e.salary),$("#pe_designation").val(e.designation),$("#pe_employment_type").val(e.employment_type),$("#employee_history_modal").modal("hide"),setTimeout(function(){$("#prev_employment_modal").modal("show")},500)}})}),$(document).on("click",".add_educational",function(){var e=$(this).attr("id");$("#eb_emp_id").val(e),$("#eb_add_edit").val("add"),$("#employee_history_modal").modal("hide"),setTimeout(function(){$("#educational_background_modal").modal("show")},500)}),$(document).on("submit","#educational_background_form",function(e){e.preventDefault();var t=$(".save_educational_background"),a=document.getElementsByClassName("save_educational_background")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_educational_background",method:"POST",data:$(this).serialize(),success:function(e){$("#educational_background_modal").modal("hide"),setTimeout(function(){$("#employee_history_modal").modal("show")},500),a.disabled=!1,t.html("SAVE CHANGES"),r(e),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok")},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".edit_educational_background",function(){var e=$(this).attr("id");$.ajax({url:"/get_educational_background/"+e,method:"get",dataType:"json",success:function(e){console.log(e),$("#eb_add_edit").val("edit"),$("#eb_id").val(e.id),$("#eb_emp_id").val(e.emp_id),$("#eb_school").val(e.school),$("#eb_start").val(e.start),$("#eb_end").val(e.end),$("#eb_course").val(e.course_id).trigger("change"),$("#eb_level").val(e.level),$("#eb_awards").val(e.awards),$("#employee_history_modal").modal("hide"),setTimeout(function(){$("#educational_background_modal").modal("show")},500)}})}),$(document).on("click",".family_employee",function(){var e=$(this).attr("id");$.ajax({url:"/get_employee/"+e,method:"get",dataType:"json",success:function(e){$(".add_emergency").attr("id",e.employee.id),$(".add_spouse").attr("id",e.employee.id),$(".add_child").attr("id",e.employee.id),$("#employee_family_modal .modal-title").text(e.employee.fname+" "+e.employee.lname)},error:function(e){swal("Error!","Something went wrong, try again.","error"),button.disabled=!1,input.html("SAVE CHANGES")}}),d(e),$("#employee_family_modal").modal("show")}),$(document).on("click",".add_emergency",function(){var e=$(this).attr("id");$("#e_emp_id").val(e),$("#e_add_edit").val("add"),$("#employee_family_modal").modal("hide"),setTimeout(function(){$("#emergency_modal").modal("show")},500)}),$(document).on("click",".edit_employee_emergency",function(){var e=$(this).attr("id");$.ajax({url:"/get_employee_emergency/"+e,method:"get",dataType:"json",success:function(e){$("#e_add_edit").val("edit"),$("#e_id").val(e.id),$("#e_emp_id").val(e.emp_id),$("#e_fname").val(e.fname),$("#e_mname").val(e.mname),$("#e_lname").val(e.lname),$("#e_relationship").val(e.relationship),$("#e_contact").val(e.contact),$("#employee_family_modal").modal("hide"),setTimeout(function(){$("#emergency_modal").modal("show")},500)}})}),$(document).on("submit","#emergency_form",function(e){e.preventDefault();var t=$(".save_emergency"),a=document.getElementsByClassName("save_emergency")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_employee_emergency",method:"POST",data:$(this).serialize(),success:function(e){$("#emergency_modal").modal("hide"),a.disabled=!1,t.html("SAVE CHANGES"),d(e),m(e),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok")},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".delete_employee_emergency",function(){var e=$(this).attr("id");swal({title:"Are you sure?",text:"You are about to delete an employee emergency number",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_employee_emergency/"+e,method:"get",type:"json",success:function(e){d(e),m(e),notif("Deleted!","This Emergency Number has been Deleted","success","glyphicon-ok")}})})}),$(document).on("click",".add_spouse",function(){var e=$(this).attr("id");$("#s_emp_id").val(e),$("#s_add_edit").val("add"),$("#employee_family_modal").modal("hide"),setTimeout(function(){$("#spouse_modal").modal("show")},500)}),$(document).on("click",".edit_employee_spouse",function(){var e=$(this).attr("id");$.ajax({url:"/get_employee_spouse/"+e,method:"get",dataType:"json",success:function(e){$("#s_add_edit").val("edit"),$("#s_id").val(e.id),$("#s_emp_id").val(e.emp_id),$("#s_fname").val(e.fname),$("#s_mname").val(e.mname),$("#s_lname").val(e.lname),$("#s_birthdate").val(e.birthdate),$("#s_contact").val(e.contact),$("#employee_family_modal").modal("hide"),setTimeout(function(){$("#spouse_modal").modal("show")},500)}})}),$(document).on("submit","#spouse_form",function(e){e.preventDefault();var t=$(".save_spouse"),a=document.getElementsByClassName("save_spouse")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_employee_spouse",method:"POST",data:$(this).serialize(),success:function(e){$("#spouse_modal").modal("hide"),a.disabled=!1,t.html("SAVE CHANGES"),d(e),m(e),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok")},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".delete_employee_spouse",function(){var e=$(this).attr("id");swal({title:"Are you sure?",text:"You are about to delete an employee spouse",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_employee_spouse/"+e,method:"get",type:"json",success:function(e){d(e),m(e),notif("Deleted!","Spouse has been Deleted","success","glyphicon-ok")}})})}),$(document).on("click",".add_child",function(){var e=$(this).attr("id");$("#c_emp_id").val(e),$("#c_add_edit").val("add"),$("#employee_family_modal").modal("hide"),setTimeout(function(){$("#child_modal").modal("show")},500)}),$(document).on("click",".edit_employee_child",function(){var e=$(this).attr("id");$.ajax({url:"/get_employee_child/"+e,method:"get",dataType:"json",success:function(e){$("#c_add_edit").val("edit"),$("#c_id").val(e.id),$("#c_emp_id").val(e.emp_id),$("#c_fname").val(e.fname),$("#c_mname").val(e.mname),$("#c_lname").val(e.lname),$("#c_birthdate").val(e.birthdate),$("#c_gender").val(e.gender).trigger("change"),$("#employee_family_modal").modal("hide"),setTimeout(function(){$("#child_modal").modal("show")},500)}})}),$(document).on("submit","#child_form",function(e){e.preventDefault();var t=$(".save_child"),a=document.getElementsByClassName("save_child")[0];a.disabled=!0,t.html("SAVING..."),$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/save_employee_child",method:"POST",data:$(this).serialize(),success:function(e){$("#child_modal").modal("hide"),a.disabled=!1,t.html("SAVE CHANGES"),d(e),m(e),notif("Success!","Record has been saved to the Database!","success","glyphicon-ok")},error:function(e){swal("Error!","Something went wrong, try again.","error"),a.disabled=!1,t.html("SAVE CHANGES")}})}),$(document).on("click",".delete_employee_child",function(){var e=$(this).attr("id");swal({title:"Are you sure?",text:"You are about to delete an employee child",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(function(t){t.value&&$.ajax({headers:{"X-CSRF-Token":$('meta[name="csrf-token"]').attr("content")},url:"/delete_employee_child/"+e,method:"get",type:"json",success:function(e){d(e),m(e),notif("Deleted!","Child has been Deleted","success","glyphicon-ok")}})})}),$("#eb_course").select2({placeholder:"Select Course",ajax:{url:"/courseAll",dataType:"json",data:function(e){return{name:e.term,page:e.page}},processResults:function(e){return{results:e.results}}}})})}});